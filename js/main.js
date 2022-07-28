import {GameObjects} from './gameObjects.js';
import {Player} from './player.js';
import {Enemy} from './enemy.js';
import {Ship} from './ship.js';
import {Explosion, animateExplosion} from './explosion.js';
import {Hitbox, checkCollisionWith} from './hitbox.js';

//Canvas Variables
const can = document.getElementById('gra'), ctx = can.getContext('2d');
const canWidth = can.width, canHeight = can.height;
export {canWidth};
//Canvas Variables

const ships = [
    new Ship(require('./img/ships/1.png'), 124, 135, [3, 45, 113, 45], 8),
    new Ship(require('./img/ships/2.png'), 120, 185, [18, 13, 92, 13], 11),
    new Ship(require('./img/ships/3.png'), 116, 110, [17, 0, 97, 0], 2),
    new Ship(require('./img/ships/4.png'), 199, 112, [74, 70,81,65,94,73,101,70,294,70,301,75,313,65,320,70], 4),
    new Ship(require('./img/ships/5.png'), 134, 199, [27, 61,98, 61], 10),
    new Ship(require('./img/ships/6.png'), 230, 336, [27,44,37,38,201,44,192,38,101,14,127,14,106,1,122,1], 2),
    new Ship(require('./img/ships/7.png'), 168, 104, [24, 140], 4)
]; 

//OBJECTS
const playerShipNumber = 2 - 1;
const playerHitbox = new Hitbox(canWidth / 2 - 60, 600, ships[playerShipNumber].width, ships[playerShipNumber].height);
let playerIsAlive = true;
let gamePlay = 'start';
const player1 = new Player(canWidth / 2 - 124, 600, 124, 135, playerHitbox, 4, ships[playerShipNumber]);
const gameObjects = new GameObjects();
gameObjects.player = player1;
//OBJECTS
console.log(player1);

const explosionFrames = new Image();
explosionFrames.src = require('./img/explosions2.png');

const deadInfoImage = new Image();
deadInfoImage.src = require('./img/youdead.png');

const logoImage = new Image();
logoImage.src = require('./img/logo.png');

const startInfoImage = new Image();
startInfoImage.src = require('./img/startinfo.png');
const startAnimationImage = new Image();
startAnimationImage.src = require('./img/startanimation.png');
let startAnimationFrame = 0;

setInterval(()=>{
    startAnimationFrame++;
    if (startAnimationFrame === 2) {
        startAnimationFrame = 0;
    }
},1000);

gameObjects.explosions.push(new Explosion(250,250,explosionFrames, 50));

//IMG
console.log(gameObjects.explosions);
//IMG

can.addEventListener('click', e => {
   
});

can.addEventListener('contextmenu', e => {
    e.preventDefault();
});

let keyboard = [37, 39];

let playerShootInterval;
let playerIsShooting = false;

document.addEventListener('keydown', function (e) {
    const {keyCode} = e;
    if(keyCode === 37) {
        keyboard[keyCode] = true;
    } else if (keyCode === 39) {
        keyboard[keyCode] = true;    
    } else if (keyCode === 70 && !playerIsShooting) {
        playerShootInterval = setInterval(player1.shoot, 225, gameObjects, player1);
        playerIsShooting = true;
    }
}, true);

document.addEventListener('visibilitychange', function () {
    if (document.visibilityState === 'visible' && gamePlay !== 'start' && gamePlay !== 'off') {
        gamePlay = 'exitpause';
    } else if (gamePlay !== 'start' && gamePlay !== 'off'){
        gamePlay = 'pause';
    }
    console.log(gamePlay);
});

document.addEventListener('keyup', function (e) {
    const {keyCode} = e;
    keyboard[keyCode] = false;
    
    console.log(keyCode);
    if (keyCode === 70) {
//        gameObjects.player.shoot(gameObjects);
        clearInterval(playerShootInterval);
        playerIsShooting = false;
    } else if (keyCode === 32 && gamePlay === 'start' || keyCode === 80 && gamePlay === 'exitpause') {
        gamePlay = 'on';
    }
}, true);

function drawAll() {
    ctx.clearRect(0, 0, canWidth,canHeight);
    drawText(50, 50, 'SpaceWar', 'black', 50);
    for (const bullet of gameObjects.bullets) {
        bullet.draw(ctx);
    }
    for (const enemy of gameObjects.enemies) {
        enemy.draw(ctx, can);
    }
    for (const explosion of gameObjects.explosions) {
        explosion.draw(ctx);
    }
    if (playerIsAlive) {
        gameObjects.player.draw(ctx, player1.ship);        
    }
    if (gamePlay === 'off') {
        ctx.fillStyle = '#591919';
        ctx.fillRect(0, 0, canWidth, canHeight);
        ctx.drawImage(deadInfoImage, (canWidth - 400) / 2, (canHeight - 225) / 2);
    } else if (gamePlay === 'start') {
        ctx.fillStyle = '#4724e1';
        ctx.fillRect(0, 0, canWidth, canHeight);
        ctx.drawImage(logoImage, (canWidth - 900) / 2, (canHeight - 560) / 2 - 80);
        ctx.drawImage(startAnimationImage, 0, 100 * startAnimationFrame, 900, 100, (canWidth - 900) / 2, 600, 900, 100);
    } else if (gamePlay === 'exitpause') {
        drawText(150, 150, 'Press P to return');
    }
    requestAnimationFrame(drawAll);
}

function drawText(textX, textY, textToDisplay, fontColor, fontSize, fontFamily = 'Monospace') {
    ctx.fillStyle = fontColor;
    ctx.font = fontSize + 'px ' + fontFamily;
    ctx.fillText(textToDisplay, textX, textY);
}

function generateEnemy() {
    if (gamePlay === 'on') {
        const x = Math.floor(Math.random() * canWidth - 150) + 120;
        let speed = Math.floor(Math.random() * 9) + 7;
        let shipNumber = Math.floor(Math.random() * ships.length) + 0;
        const y = -120;
        const newHitbox = new Hitbox(x, y, 120, 50);
        let shootingEnemy = false;

        const randomShooting = Math.random();
        if (randomShooting > 0.6) {
            shootingEnemy = true;
            speed = Math.floor(Math.random() * 6) + 4;
        }

        gameObjects.enemies.push(new Enemy(x, y, ships[shipNumber].width, ships[shipNumber].height, newHitbox, speed, shootingEnemy, ships[shipNumber]));
    }
}

function enemyShoot() {
    if (gamePlay === 'on') {
        for (const enemy of gameObjects.enemies) {
            if (enemy.shooting && enemy.reload <= 0) {
                enemy.shoot(gameObjects);
                const reload = Math.floor(Math.random() * 450) + 250;
                enemy.reload = reload;
            } else {
                enemy.reload -= 10;
            }
        }
    }
}

function gameLoop() {
    if (gamePlay === 'on') {
        updateHitboxs();
        if (keyboard[37]) {
            gameObjects.player.move('Left');
        } else if (keyboard[39]) {
            player1.move('Right');
        }
        gameObjects.bullets.forEach((bullet, id) => {
            bullet.move();
            if (bullet.destroy) {
                gameObjects.bullets.splice(id, 1);
            }
        });

        gameObjects.enemies.forEach((enemy, enemyId) => {
            enemy.move();
            if (checkCollisionWith(player1.hitbox, enemy.hitbox)) {
                enemy.destroy = true;
                enemy.destroyAnimation = true;
                let width;
                if (enemy.ship.height > enemy.ship.width) {
                    width = enemy.ship.width - enemy.ship.width * 12 / 100;
                } else {
                    width = enemy.ship.height - enemy.ship.height * 12 / 100;
                }
                gameObjects.explosions.push(new Explosion(enemy.x, enemy.y, explosionFrames, width));
                gameObjects.enemies.splice(enemyId, 1);
                playerIsAlive = false;
                gamePlay = 'off';
                gameObjects.explosions.push(new Explosion(player1.x, player1.y, explosionFrames, player1.ship.width));
            }
            gameObjects.bullets.forEach((bullet, bulletId) => {
                if (checkCollisionWith(enemy.hitbox, bullet.hitbox)) {
                    if (bullet.owner === 'player') {
                        gameObjects.enemies.splice(enemyId, 1);
                        gameObjects.bullets.splice(bulletId, 1);
                        let width;
                        if (enemy.ship.height > enemy.ship.width) {
                            width = enemy.ship.width - enemy.ship.width * 12 / 100;
                        } else {
                            width = enemy.ship.height - enemy.ship.height * 12 / 100;
                        }
                        gameObjects.explosions.push(new Explosion(enemy.x, enemy.y, explosionFrames, width));
                    }
                }
            });
        });
    }
}

function updateHitboxs()
{
    player1.hitbox.x = player1.x;
    
    for (const bullet of gameObjects.bullets) {
        bullet.hitbox.y = bullet.y;
    }
    
    for (const enemy of gameObjects.enemies) {
        enemy.hitbox.y = enemy.y;
    }
}

function animateAll () {
    animateExplosion(gameObjects);
}

setInterval(generateEnemy, 650);
setInterval(generateEnemy, 850);
setInterval(enemyShoot, 10);
setInterval(gameLoop, 12);
setInterval(animateAll, 65);
requestAnimationFrame(drawAll);