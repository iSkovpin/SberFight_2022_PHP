<?php

/**
 * Implement function getResult
 */
function getResult(array $actions): int
{
    $isOn = false;
    $isLogged = false;
    $prevClick = false;
    $launches = 0;

    foreach ($actions as $action) {
        if ($action === "power") {
            $isOn = !$isOn;
            if ($isOn === false) {
                $isLogged = false;
                $prevClick = false;
            }
        } elseif ($action === "keystrokes") {
            if (!$isOn) {
                continue;
            }
            $isLogged = true;

        } elseif ($action === "click") {
            if (!$isOn || !$isLogged) {
                continue;
            }
            if ($prevClick === false) {
                $prevClick = true;
                continue;
            } elseif ($prevClick === true) {
                $launches++;
                $prevClick = false;
            }
        }
        if ($prevClick === true) {
            $prevClick = false;
        }
    }

    return $launches;
}
