// Timer  
const _days = document.querySelector("#days")
const _hours = document.querySelector("#hours")
const _minutes = document.querySelector("#minutes")
const _seconds = document.querySelector("#seconds")
const _deadline = document.querySelector("#deadline");

// makeDraw 
let boxes = document.querySelector('#dashboard .boxes');
let cubeWrapper = document.querySelectorAll(".cube-wrapper");
let _makeDraw = document.querySelector("#dashboard #make-draw");
let draw = document.querySelector("#dashboard #make-draw input[type='hidden']");

let cback = document.querySelectorAll(".back");
let ctop = document.querySelectorAll(".top");
let cleft = document.querySelectorAll(".left");
let cright = document.querySelectorAll(".right");
let glow = document.querySelectorAll(".hexagon");
let powerup = document.querySelectorAll(".powerup");

let counterInterval;
let xDrawsJson = undefined;
let c = 0;

if (_deadline) {
    countdown()
    counterInterval = setInterval(countdown, 1000);
};

if (boxes) {
    fetchBoxes();
    setInterval(fetchBoxes, 250);
}

if (_makeDraw) {
    _makeDraw.addEventListener('submit', (e) => {
        e.preventDefault();

        if (draw.value == '') alert('Veuillez sélectionner une boîte avant de poursuivre.')
        else _makeDraw.submit();
    })
}

function fetchBoxes() {
    var xhr = new XMLHttpRequest();

    xhr.onreadystatechange = function () {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                var responseData = JSON.parse(xhr.responseText);
                handleDataUpdate(responseData);
            }
        }
    };

    xhr.open('GET', 'http://localhost/b2m-rh/app/lib/draws/get/?selected=' + encodeURIComponent(draw.value), true);
    xhr.send();
}

function handleDataUpdate(data) {
    if (JSON.stringify(xDrawsJson) == JSON.stringify(data)) return;
    boxes.innerHTML = '';
    xDrawsJson = data;

    Object.keys(data).forEach(key => {
        const cubeWrapperElem = document.createElement('div');
        const hexagonElem = document.createElement('div');
        const backCubeElem = document.createElement('div');
        const topCubeElem = document.createElement('div');
        const leftCubeElem = document.createElement('div');
        const rightCubeElem = document.createElement('div');
        const powerupElem = document.createElement('div');

        cubeWrapperElem.classList.add('cube-wrapper', 'h-40', 'w-40', 'relative', 'flex', 'justify-center', 'items-center', 'cursor-pointer');
        cubeWrapperElem.setAttribute('id', key);
        data[key].split(' ').forEach(Element => {
            if (Element.trim() !== '')
                cubeWrapperElem.classList.add(Element);
        });

        if (draw.value === key) {
            if (cubeWrapperElem.classList.contains('reserved')) {
                draw.value = '';
                cubeWrapperElem.classList.remove('selected');
            }
        }

        hexagonElem.classList.add('hexagon', 'absolute');
        backCubeElem.classList.add('cube', 'back', 'h-40', 'w-40', 'absolute', 'top-0', 'left-0');
        topCubeElem.classList.add('cube', 'top', 'h-40', 'w-40', 'absolute', 'top-0', 'left-0');
        leftCubeElem.classList.add('cube', 'left', 'h-40', 'w-40', 'absolute', 'top-0', 'left-0');
        rightCubeElem.classList.add('cube', 'right', 'h-40', 'w-40', 'absolute', 'top-0', 'left-0');
        powerupElem.classList.add('powerup', 'absolute');

        cubeWrapperElem.appendChild(hexagonElem);
        cubeWrapperElem.appendChild(backCubeElem);
        cubeWrapperElem.appendChild(topCubeElem);
        cubeWrapperElem.appendChild(leftCubeElem);
        cubeWrapperElem.appendChild(rightCubeElem);
        cubeWrapperElem.appendChild(powerupElem);

        boxes.appendChild(cubeWrapperElem);
    });

    cubeWrapper = document.querySelectorAll(".cube-wrapper");
    cubeWrapper.forEach((cube) => {
        if (!cube.classList.contains('reserved'))
            cube.addEventListener("click", () => {
                if (draw.value == '') {
                    cube.classList.add('selected');
                    draw.value = cube.getAttribute('id');
                } else {
                    if (draw.value == cube.getAttribute('id')) {
                        cube.classList.remove('selected');
                        draw.value = '';
                    } else {
                        cube.classList.add('selected');
                        draw.value = cube.getAttribute('id');
                    }
                }
            });
    });
}

function formatTime(time) {
    return time < 10 ? `0${time}` : time;
}

function countdown() {
    const deadline = new Date(_deadline.getAttribute('value'));
    const currentDate = new Date();

    const totalSeconds = (deadline - currentDate) / 1000;

    let days = Math.floor(totalSeconds / 3600 / 24);
    let hours = Math.floor(totalSeconds / 3600) % 24;
    let minutes = Math.floor(totalSeconds / 60) % 60;
    let seconds = Math.floor(totalSeconds % 60);

    if ((totalSeconds <= 0)) {
        if (_deadline.getAttribute('mode') == 'Register') {
            location.reload();
            return;
        } else {
            clearInterval(counterInterval);
            days = 0;
            hours = 0;
            minutes = 0;
            seconds = 0;
        }
    }

    _days.innerHTML = formatTime(days);
    _hours.innerHTML = formatTime(hours);
    _minutes.innerHTML = formatTime(minutes);
    _seconds.innerHTML = formatTime(seconds);
}