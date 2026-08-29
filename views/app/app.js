function setStatusColor(isOnline) {
    document.body.style.backgroundColor =
        isOnline ? "lightgreen" : "lightcoral";

    document.querySelector('#status').innerHTML =
        isOnline ? '🟩' : '🟥';
}

function logout(event) {
    //event.preventDefault();
    localStorage.removeItem(window.PWAConfig.storage_prefix + '_user');

    showUserInfo(false);

    if (!navigator.onLine) {
        return;
    }

    window.location.href = event.currentTarget.href;
}

window.logout = logout;  // since this file is included as a module

function showUserInfo(user) {
    let link =
        '<a href="/site/choose-organizational-unit?return=%2Fdigital-receipts%2Fapp">Login</a>';

    if (!navigator.onLine) {
        link = window.digitalReceiptConfig.messages.goOnlineToLogin;
    }

    let userInfo = '🤷';

    if (user) {
        link =
            '<a href="/site/logout?return=%2Fdigital-receipts%2Fapp" onclick="logout(event)">Logout</a>';
            //'<a href="#" onclick="logout(event)">Logout</a>';

        userInfo = user.first_name;
        
        if (user.organizational_unit) {
            userInfo += ` (${user.organizational_unit.name})`;
        }
        else {
            userInfo += JSON.stringify(user); // for debug
        }
    }

    document.querySelector('#user').innerHTML =
        `${userInfo} ${link}`;
}


setStatusColor(navigator.onLine);

window.addEventListener("online", () => {
    setStatusColor(true);
    showUserInfo(user);
});

window.addEventListener("offline", () => {
    setStatusColor(false);
});

let user = false;

showUserInfo(user);

// The promise resolves only when authentication is finished.
export const userReady = (async () => {

    const storedUser = localStorage.getItem(window.PWAConfig.storage_prefix + '_user');
    
    let client = 'unknown';
    
    if (navigator.standalone !== undefined) { // for iOS
        client = navigator.standalone ? 'standalone': 'browser';
    }
    else {
        client = window.matchMedia('(display-mode: standalone)').matches ? 'standalone' : 'browser'; // for other devices
    }

    if (storedUser) {
        user = JSON.parse(storedUser);

        showUserInfo(user);

        return user;
    }

    try {
        const response = await fetch(`/api/v1/who-am-i?client=${client}`);

        if (response.ok) {
            user = await response.json();

            localStorage.setItem(
                window.PWAConfig.storage_prefix + '_user',
                JSON.stringify(user)
            );
            showUserInfo(user);

            return user;
        }

        if (response.status === 401) {
            showUserInfo(false);
            return false;
        }

        throw new Error(`HTTP error ${response.status}`);

    } catch (error) {
        console.error('Error checking auth status:', error);
        return false;
    }
})();
