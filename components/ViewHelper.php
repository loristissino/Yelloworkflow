<?php

namespace app\components;

use yii\helpers\Html;

class ViewHelper {
    public static function convertHashtagsToLinks($text, $url)
    {
        // Use a regular expression to find hashtags in the text
        $pattern = '/#(\w+)/';
        
        // Replace the hashtags with HTML links
        $replacement = '<a href="' . $url . '&hash=$1">#$1</a>';
        
        // Perform the replacement
        $newText = preg_replace($pattern, $replacement, $text);
        
        return $newText;
    }
    
    /**
     * Renders a PHP array/object as syntax-highlighted HTML JSON.
     *
     * @param mixed $data The PHP array or object representing the JSON structure.
     * @param int $indent The current indentation level (for internal recursive calls).
     * @return string The HTML string.
     */
    public static function asJsonPrettyColorized($data, $indent = 0)
    {
        $html = '';
        $indentSpaces = str_repeat('    ', $indent); // 4 spaces for indentation

        if (is_array($data)) {
            // Determine if it's an associative array (JSON object) or sequential array (JSON array)
            $isAssoc = array_keys($data) !== range(0, count($data) - 1);

            if (empty($data)) {
                return $isAssoc ? Html::tag('span', '{}', ['class' => 'json-brace']) : Html::tag('span', '[]', ['class' => 'json-bracket']);
            }

            $html .= $isAssoc ? Html::tag('span', '{', ['class' => 'json-brace']) : Html::tag('span', '[', ['class' => 'json-bracket']);
            $html .= "\n"; // New line after opening brace/bracket

            $i = 0;
            foreach ($data as $key => $value) {
                $html .= $indentSpaces . '    '; // Indent for each item
                if ($isAssoc) {
                    $html .= Html::tag('span', '"' . Html::encode($key) . '"', ['class' => 'json-key']);
                    $html .= Html::tag('span', ': ', ['class' => 'json-colon']);
                }
                $html .= self::asJsonPrettyColorized($value, $indent + 1); // Recursive call for nested elements
                if ($i < count($data) - 1) {
                    $html .= Html::tag('span', ',', ['class' => 'json-comma']);
                }
                $html .= "\n"; // New line after each item
                $i++;
            }
            $html .= $indentSpaces; // Indent for closing brace/bracket
            // --- CORRECTED LINE HERE ---
            $html .= Html::tag('span', $isAssoc ? '}' : ']', ['class' => 'json-brace']); // Closing brace/bracket
            // --- END CORRECTED LINE ---

        } elseif (is_object($data)) {
            // Treat generic objects like associative arrays for display purposes
            return self::asJsonPrettyColorized((array)$data, $indent);

        } elseif (is_string($data)) {
            $html .= Html::tag('span', '"' . Html::encode($data) . '"', ['class' => 'json-string']);
        } elseif (is_numeric($data)) {
            $html .= Html::tag('span', Html::encode($data), ['class' => 'json-number']);
        } elseif (is_bool($data)) {
            $html .= Html::tag('span', $data ? 'true' : 'false', ['class' => 'json-boolean']);
        } elseif (is_null($data)) {
            $html .= Html::tag('span', 'null', ['class' => 'json-null']);
        }

        return $html;
    }

}
