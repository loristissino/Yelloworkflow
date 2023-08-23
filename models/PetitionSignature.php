<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "petition_signatures".
 *
 * @property int $id
 * @property int $petition_id
 * @property string|null $petition_slug
 * @property string $email
 * @property string $first_name
 * @property string $last_name
 * @property int|null $yob
 * @property string|null $district
 * @property string|null $gender
 * @property string $message
 * @property string $accepted_terms
 * @property string $agreed_to_keep_me_updated
 * @property string $agreed_to_allow_to_be_contacted
 * @property string|null $confirmation_code
 * @property int|null $created_at
 * @property int|null $updated_at
 * @property int|null $confirmed_at
 * @property int|null $reminded_at
 * @property int|null $validated_at
 *
 * @property Petition $petition
 */
class PetitionSignature extends \yii\db\ActiveRecord
{
    public $verifyCode;
    public $processingUrl;
    public $petition_slug;
    public $captcha;
    public $agreed_to_keep_me_updated;
    public $agreed_to_allow_to_be_contacted;
        
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'petition_signatures';
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['petition_slug', 'email', 'first_name', 'last_name', 'accepted_terms'], 'required'],
            [['petition_id', 'created_at', 'updated_at', 'confirmed_at', 'reminded_at', 'validated_at', 'yob'], 'integer'],
            [['yob'], 'number', 'min' => 1900, 'max'=>date('Y')-18],
            [['district', 'gender'], 'string', 'max' => 10],
            [['message'], 'string', 'max' => 1024],
            [['petition_slug'], 'string'],
            [['email'], 'email'],
            [['email', 'first_name', 'last_name'], 'string', 'max' => 120],
//            [['accepted_terms', 'agreed_to_keep_me_updated', 'agreed_to_allow_to_be_contacted'], 'validatePrivacyFields'],
            [['petition_id'], 'exist', 'skipOnError' => true, 'targetClass' => Petition::className(), 'targetAttribute' => ['petition_id' => 'id']],
            ['email', 'unique', 'targetAttribute' => ['petition_id', 'email']]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'petition_id' => Yii::t('app', 'Petition'),
            'petition_slug' => Yii::t('app', 'Petition Slug'),
            'email' => Yii::t('app', 'Email'),
            'first_name' => Yii::t('app', 'First Name'),
            'last_name' => Yii::t('app', 'Last Name'),
            'yob' => Yii::t('app', 'Year of Birth'),
            'district' => Yii::t('app', 'District / State of Residence'),
            'gender' => Yii::t('app', 'Gender'),
            'message' => Yii::t('app', 'Message'),
            'accepted_terms' => Yii::t('app', 'Accepted Terms'),
            'confirmation_code' => Yii::t('app', 'Confirmation Code'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'confirmed_at' => Yii::t('app', 'Confirmed At'),
            'reminded_at' => Yii::t('app', 'Reminded At'),
            'validated_at' => Yii::t('app', 'Validated At'),
        ];
    }

/*
    public function validatePrivacyFields($attribute, $params, $validator)
    {
        file_put_contents('log_petition' . date('H:i:s') . '.txt', $_SERVER['QUERY_STRING']);
        if (!isset($this->accepted_terms) || $this->accepted_terms == '') {
            $this->addError('acceèpted_terms', Yii::t('app', 'You must accept the privacy terms to proceeed.'));
        }
    }
*/    
    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }
        if ($insert) {
            $this->accepted_terms = date('Y-m-d H:i:s')
                . ';' . ($this->agreed_to_keep_me_updated ? 'yes-updates': 'no-updates')
                . ';' . ($this->agreed_to_allow_to_be_contacted ? 'yes-contacts': 'no-contacts');
        }
        return true;
    }
    
    public function setSlug()
    {
        if (!$this->petition_slug) {
            $this->petition_slug = $this->petition->slug;
        }
        return true;
    }

    public function prepareConfirmationEmail($key)
    {
        $message = $this->_prepareMessage($key, 'confirm');
        $message->headers = sprintf("X-RequestFrom: %s\nX-PetitionSlug: %s", Yii::$app->getRequest()->getUserIP(), $this->petition->slug);
        return $message->save(false);
    }

    public function prepareRemindEmail($key)
    {
        $message = $this->_prepareMessage($key, 'remind');
        if ($message->save(false)) {
            $this->reminded_at = time();
            $this->save(false);
            return true;
        }
        return false;
    }
    
    private function _prepareMessage($key, $kind)
    {
        $url = Yii::$app->params['petitions'][$key]['envolve_signature_confirmation'] . '?email=' . $this->email . '&slug=' . $this->petition->slug . '&code=' . $this->confirmation_code;
        
        $html = sprintf(Yii::$app->params['petitions'][$key][$kind . '_mail_html_body'],
            $this->first_name,
            $this->petition->title,
            $url,
            $url
        );
        
        $message = new Message();
        $message->subject = Yii::$app->params['petitions'][$key][$kind . '_mail_subject'];
        $message->html_body = $html;
        $message->email = $this->email;
        $message->addressee = $this->fullName;
        $message->apikey = Yii::$app->params['petitions'][$key]['mailer_apikey'];
        return $message;
    }
    

    /**
     * Gets query for [[Petition]].
     *
     * @return \yii\db\ActiveQuery|PetitionQuery
     */
    public function getPetition()
    {
        return $this->hasOne(Petition::className(), ['id' => 'petition_id']);
    }
    
    public function getFullName($lastname=true)
    {
        $last = $lastname ? $this->last_name : substr($this->last_name, 0, 1) . '.';
        return sprintf("%s %s", $this->first_name, $last);
    }
    
    public function generateConfirmationCode()
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < 10; $i++) {
            $randomInt = random_int(0, $charactersLength - 1);
            $randomString .= $characters[$randomInt];
        }
        $this->confirmation_code = $randomString;
    }
    
    public static function confirmSignature($petition_id, $email, $code)
    {
        $signature = PetitionSignature::find()
            ->ofPetition($petition_id)
            ->withEmail($email)
            ->one();

        if (!$signature) {
            return 0;
        }
  
        if ($signature->confirmed_at) {
            return 2;
        }
        
        $signature->confirmed_at = time();
        $signature->save(false); // we don't need validation at this point
        return 1;
    }
    
    public static function getDistricts()
    {
        // TODO: this is hardcoded - should change
        return [
            '--' => "------",
            'AG' => "Agrigento", 
            'AL' => "Alessandria", 
            'AN' => "Ancona", 
            'AO' => "Aosta", 
            'AP' => "Ascoli-Piceno", 
            'AQ' => "L'Aquila", 
            'AR' => "Arezzo", 
            'AT' => "Asti", 
            'AV' => "Avellino", 
            'BA' => "Bari", 
            'BG' => "Bergamo", 
            'BI' => "Biella", 
            'BL' => "Belluno", 
            'BN' => "Benevento", 
            'BO' => "Bologna", 
            'BR' => "Brindisi", 
            'BS' => "Brescia", 
            'BT' => "Barletta-Andria-Trani", 
            'BZ' => "Bolzano", 
            'CA' => "Cagliari", 
            'CB' => "Campobasso", 
            'CE' => "Caserta", 
            'CH' => "Chieti", 
            'CL' => "Caltanissetta", 
            'CN' => "Cuneo", 
            'CO' => "Como", 
            'CR' => "Cremona", 
            'CS' => "Cosenza", 
            'CT' => "Catania", 
            'CZ' => "Catanzaro", 
            'EN' => "Enna", 
            'FC' => "Forli-Cesena", 
            'FE' => "Ferrara", 
            'FG' => "Foggia", 
            'FI' => "Firenze", 
            'FM' => "Fermo", 
            'FR' => "Frosinone", 
            'GE' => "Genova", 
            'GO' => "Gorizia", 
            'GR' => "Grosseto", 
            'IM' => "Imperia", 
            'IS' => "Isernia", 
            'KR' => "Crotone", 
            'LC' => "Lecco", 
            'LE' => "Lecce", 
            'LI' => "Livorno", 
            'LO' => "Lodi", 
            'LT' => "Latina", 
            'LU' => "Lucca", 
            'MB' => "Monza-Brianza", 
            'MC' => "Macerata", 
            'ME' => "Messina", 
            'MI' => "Milano", 
            'MN' => "Mantova", 
            'MO' => "Modena", 
            'MS' => "Massa-Carrara", 
            'MT' => "Matera", 
            'NA' => "Napoli", 
            'NO' => "Novara", 
            'NU' => "Nuoro", 
            'OR' => "Oristano", 
            'PA' => "Palermo", 
            'PC' => "Piacenza", 
            'PD' => "Padova", 
            'PE' => "Pescara", 
            'PG' => "Perugia", 
            'PI' => "Pisa", 
            'PN' => "Pordenone", 
            'PO' => "Prato", 
            'PR' => "Parma", 
            'PT' => "Pistoia", 
            'PU' => "Pesaro-Urbino", 
            'PV' => "Pavia", 
            'PZ' => "Potenza", 
            'RA' => "Ravenna", 
            'RC' => "Reggio-Calabria", 
            'RE' => "Reggio-Emilia", 
            'RG' => "Ragusa", 
            'RI' => "Rieti", 
            'RM' => "Roma", 
            'RN' => "Rimini", 
            'RO' => "Rovigo", 
            'SA' => "Salerno", 
            'SI' => "Siena", 
            'SO' => "Sondrio", 
            'SP' => "La Spezia", 
            'SR' => "Siracusa", 
            'SS' => "Sassari", 
            'SU' => "Sud Sardegna", 
            'SV' => "Savona", 
            'TA' => "Taranto", 
            'TE' => "Teramo", 
            'TN' => "Trento", 
            'TO' => "Torino", 
            'TP' => "Trapani", 
            'TR' => "Terni", 
            'TS' => "Trieste", 
            'TV' => "Treviso", 
            'UD' => "Udine", 
            'VA' => "Varese", 
            'VB' => "Verbania", 
            'VC' => "Vercelli", 
            'VE' => "Venezia", 
            'VI' => "Vicenza", 
            'VR' => "Verona", 
            'VT' => "Viterbo", 
            'VV' => "Vibo-Valentia", 

            'AUT' => "Austria",
            'BEL' => "Belgio",
            'BGR' => "Bulgaria",
            'CYP' => "Cipro",
            'HRV' => "Croazia",
            'DNK' => "Danimarca",
            'EST' => "Estonia",
            'FIN' => "Finlandia",
            'FRA' => "Francia",
            'DEU' => "Germania",
            'GRC' => "Grecia",
            'IRL' => "Irlanda",
            'ITA' => "Italia",
            'LVA' => "Lettonia",
            'LTU' => "Lituania",
            'LUX' => "Lussemburgo",
            'MLT' => "Malta",
            'NLD' => "Paesi Bassi",
            'POL' => "Polonia",
            'PRT' => "Portogallo",
            'CZE' => "Repubblica ceca",
            'ROU' => "Romania",
            'SVK' => "Slovacchia",
            'SVN' => "Slovenia",
            'ESP' => "Spagna",
            'SWE' => "Svezia",
            'HUN' => "Ungheria",
            'ALB' => "Albania",
            'BLR' => "Bielorussia",
            'BIH' => "Bosnia-Erzegovina",
            'RUS' => "Federazione russa",
            'KOS' => "Kosovo",
            'MKD' => "Macedonia del Nord",
            'MDA' => "Moldova",
            'MNE' => "Montenegro",
            'SRB' => "Serbia",
            'TUR' => "Turchia",
            'UKR' => "Ucraina",
            'AND' => "Andorra",
            'GIB' => "Gibilterra",
            'GGY' => "Guernsey",
            'ISL' => "Islanda",
            'IMN' => "Isola di Man",
            'FRO' => "Isole Fær Øer",
            'JEY' => "Jersey",
            'LIE' => "Liechtenstein",
            'MCO' => "Monaco",
            'NOR' => "Norvegia",
            'GBR' => "Regno Unito",
            'SMR' => "San Marino",
            'VAT' => "Stato della Città del Vaticano",
            'CHE' => "Svizzera",
            'DZA' => "Algeria",
            'EGY' => "Egitto",
            'LBY' => "Libia",
            'MAR' => "Marocco",
            'ESH' => "Sahara occidentale",
            'SSD' => "Sud Sudan",
            'SDN' => "Sudan",
            'TUN' => "Tunisia",
            'BEN' => "Benin",
            'BFA' => "Burkina Faso",
            'CPV' => "Capo Verde",
            'CIV' => "Costa d'Avorio",
            'GMB' => "Gambia",
            'GHA' => "Ghana",
            'GIN' => "Guinea",
            'GNB' => "Guinea-Bissau",
            'LBR' => "Liberia",
            'MLI' => "Mali",
            'MRT' => "Mauritania",
            'NER' => "Niger",
            'NGA' => "Nigeria",
            'SEN' => "Senegal",
            'SLE' => "Sierra Leone",
            'TGO' => "Togo",
            'BDI' => "Burundi",
            'COM' => "Comore",
            'ERI' => "Eritrea",
            'ETH' => "Etiopia",
            'DJI' => "Gibuti",
            'KEN' => "Kenya",
            'MDG' => "Madagascar",
            'MWI' => "Malawi",
            'MUS' => "Maurizio",
            'MOZ' => "Mozambico",
            'RWA' => "Ruanda",
            'SYC' => "Seychelles",
            'SOM' => "Somalia",
            'TZA' => "Tanzania",
            'UGA' => "Uganda",
            'ZMB' => "Zambia",
            'ZWE' => "Zimbabwe",
            'AGO' => "Angola",
            'BWA' => "Botswana",
            'CMR' => "Camerun",
            'TCD' => "Ciad",
            'COG' => "Congo",
            'SWZ' => "Eswatini",
            'GAB' => "Gabon",
            'GNQ' => "Guinea equatoriale",
            'LSO' => "Lesotho",
            'NAM' => "Namibia",
            'CAF' => "Repubblica Centrafricana",
            'COD' => "Repubblica Democratica del Congo",
            'SHN' => "Sant'Elena",
            'STP' => "Sao Tomé e Principe",
            'ZAF' => "Sudafrica",
            'SAU' => "Arabia Saudita",
            'ARM' => "Armenia",
            'AZE' => "Azerbaigian",
            'BHR' => "Bahrein",
            'ARE' => "Emirati Arabi Uniti",
            'GEO' => "Georgia",
            'JOR' => "Giordania",
            'IRN' => "Iran",
            'IRQ' => "Iraq",
            'ISR' => "Israele",
            'KWT' => "Kuwait",
            'LBN' => "Libano",
            'OMN' => "Oman",
            'PSE' => "Palestina",
            'QAT' => "Qatar",
            'SYR' => "Siria",
            'YEM' => "Yemen",
            'AFG' => "Afghanistan",
            'BGD' => "Bangladesh",
            'BTN' => "Bhutan",
            'IND' => "India",
            'KAZ' => "Kazakhstan",
            'KGZ' => "Kirghizistan",
            'MDV' => "Maldive",
            'NPL' => "Nepal",
            'PAK' => "Pakistan",
            'LKA' => "Sri Lanka",
            'TJK' => "Tagikistan",
            'TKM' => "Turkmenistan",
            'UZB' => "Uzbekistan",
            'BRN' => "Brunei Darussalam",
            'KHM' => "Cambogia",
            'CHN' => "Cina",
            'PRK' => "Corea del Nord",
            'KOR' => "Corea del Sud",
            'PHL' => "Filippine",
            'JPN' => "Giappone",
            'IDN' => "Indonesia",
            'LAO' => "Laos",
            'MYS' => "Malaysia",
            'MNG' => "Mongolia",
            'MMR' => "Myanmar/Birmania",
            'SGP' => "Singapore",
            'TWN' => "Taiwan",
            'THA' => "Thailandia",
            'TLS' => "Timor Leste",
            'VNM' => "Vietnam",
            'BMU' => "Bermuda",
            'CAN' => "Canada",
            'GRL' => "Groenlandia",
            'SPM' => "Saint Pierre e Miquelon",
            'USA' => "Stati Uniti d'America",
            'AIA' => "Anguilla",
            'ATG' => "Antigua e Barbuda",
            'ARG' => "Argentina",
            'ABW' => "Aruba",
            'BHS' => "Bahamas",
            'BRB' => "Barbados",
            'BLZ' => "Belize",
            'BOL' => "Bolivia",
            'BRA' => "Brasile",
            'CHL' => "Cile",
            'COL' => "Colombia",
            'CRI' => "Costa Rica",
            'CUB' => "Cuba",
            'CUW' => "Curaçao",
            'DMA' => "Dominica",
            'ECU' => "Ecuador",
            'SLV' => "El Salvador",
            'JAM' => "Giamaica",
            'GRD' => "Grenada",
            'GTM' => "Guatemala",
            'GUY' => "Guyana",
            'HTI' => "Haiti",
            'HND' => "Honduras",
            'CYM' => "Isole Cayman",
            'FLK' => "Isole Falkland (Malvine)",
            'TCA' => "Isole Turks e Caicos",
            'VGB' => "Isole Vergini britanniche",
            'MEX' => "Messico",
            'MSR' => "Montserrat",
            'NIC' => "Nicaragua",
            'PAN' => "Panama",
            'PRY' => "Paraguay",
            'PER' => "Perù",
            'DOM' => "Repubblica Dominicana",
            'KNA' => "Saint Kitts e Nevis",
            'VCT' => "Saint Vincent e Grenadine",
            'BLM' => "Saint-Barthélemy",
            'MAF' => "Saint-Martin (FR)",
            'LCA' => "Santa Lucia",
            'SXM' => "Sint Maarten (NL)",
            'SUR' => "Suriname",
            'TTO' => "Trinidad e Tobago",
            'URY' => "Uruguay",
            'VEN' => "Venezuela",
            'AUS' => "Australia",
            'FJI' => "Figi",
            'COK' => "Isole Cook (NZ)",
            'MHL' => "Isole Marshall",
            'PCN' => "Isole Pitcairn",
            'SLB' => "Isole Salomone",
            'KIR' => "Kiribati",
            'NRU' => "Nauru",
            'NCL' => "Nuova Caledonia",
            'NZL' => "Nuova Zelanda",
            'PLW' => "Palau",
            'PNG' => "Papua Nuova Guinea",
            'PYF' => "Polinesia francese",
            'WSM' => "Samoa",
            'FSM' => "Stati Federati di Micronesia",
            'ATF' => "Terre australi e antartiche francesi",
            'TON' => "Tonga",
            'TUV' => "Tuvalu",
            'VUT' => "Vanuatu",
            'WLF' => "Wallis e Futuna",

        ];
    }

    /**
     * {@inheritdoc}
     * @return PetitionSignatureQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new PetitionSignatureQuery(get_called_class());
    }
}
