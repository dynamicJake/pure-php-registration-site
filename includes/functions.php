<?php
include 'includes/config.new.php';

// Form input validator
function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}


/**
* Removes passed tags with their content.
*
* @param array $tagsToRemove List of tags to remove
* @param $haystack String to cleanup
* @param $formTitle String of the form subject line submitted (optional)
* @return string
*/
function removeTagsWithTheirContent(array $tagsToRemove, $haystack, $formTitle = null) {

    $currTag = '';
    $currPos = false;

    $initSearch = function (&$currTag, &$currPos, $tagsToRemove, $haystack) {
        $currTag = '';
        $currPos = false;
        foreach ($tagsToRemove as $tag) {
            $tempPos = stripos($haystack, '<'.$tag);
            if ($tempPos !== false && ($currPos === false || $tempPos < $currPos)) {
                $currPos = $tempPos;
                $currTag = $tag;
            }
        }
    };

    $substri_count = function ($haystack, $needle, $offset, $length) {
        $haystack = strtolower($haystack);
        return substr_count($haystack, $needle, $offset, $length);
    };

    $initSearch($currTag, $currPos, $tagsToRemove, $haystack);
    while ($currPos !== false) {
        $minTagLength = strlen($currTag) + 2;
        $tempPos = $currPos + $minTagLength;
        $tagEndPos = stripos($haystack, '</'.$currTag.'>', $tempPos);
        // process nested tags
        if ($tagEndPos !== false) {
            $nestedCount = $substri_count($haystack, '<' . $currTag, $tempPos, $tagEndPos - $tempPos);

            for ($i = $nestedCount; $i > 0; $i--) {
                $lastValidPos = $tagEndPos;
                $tagEndPos = stripos($haystack, '</' . $currTag . '>', $tagEndPos + 1);
                if ($tagEndPos === false) {
                    $tagEndPos = $lastValidPos;
                    break;
                }
            }
        }

        if ($tagEndPos === false) {
            // invalid html, end search for current tag
            $tagsToRemove = array_diff($tagsToRemove, [$currTag]);
        } else {
            // Send admin email & remove current tag with its content
            // TODO: send a safer email maybe with PHPMailer.
            $badmessage = "Script was found on site\n Title: $formTitle \nMessage: " . $currTag . " " . $haystack;
            mail(EMAIL,'Script was found!', $badmessage, SITE_EMAIL);

            $haystack = substr($haystack, 0, $currPos)
                // get string after "</$tag>"
                .substr($haystack, $tagEndPos + strlen($currTag) + 3);
        }

        $initSearch($currTag, $currPos, $tagsToRemove, $haystack);
    }

    return $haystack;
}


?>
