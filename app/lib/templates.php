<?php

function renderCounter($mode, $Date)
{
    return (
        '<input id="deadline" mode="' . $mode . '" type="hidden" name="deadline" value="' . $Date->format("Y-m-d H:i:s") . '">
        <div class="counter">
            <div class="timer-container">
                <div class="wrapper">
                    <div class="days">
                        <h2 id="days">JJ</h2>JOURS
                    </div>
                    <div class="hours">
                        <h2 id="hours">HH</h2>HEURES
                    </div>
                    <div class="minutes">
                        <h2 id="minutes">MM</h2>MINUTES
                    </div>
                    <div class="seconds">
                        <h2 id="seconds">SS</h2>SECONDES
                    </div>
                </div>
            </div>
        </div>'
    );
}

function renderNavBar($Page, $Admin)
{
    return (
        '<div class="sidebar">
        <img src="./img/Logo.svg" alt="">

        <div class="main-controls">
            <a href="?page=dashboard" class="nav-item ' . ($Page == "dashboard" ? "active" : "") . '"><i class="fa-solid fa-house"></i></a>' .
        ($Admin ? '<a href="?page=users" class="nav-item ' . ($Page == "users" ? "active" : "") . '"><i class="fa-solid fa-user-group"></i></a>' : '') .
        '<a href="?page=settings" class="nav-item ' . ($Page == "settings" ? "active" : "") . '"><i class="fa-solid fa-sliders"></i></a>
        </div>

        <div class="controls">
            <a class="nav-item" href="?logout=true"><i class="fa-solid fa-power-off"></i></a>
        </div>
    </div>'
    );
}
