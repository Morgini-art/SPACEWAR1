import {canWidth} from './main.js';
import {Bullet} from './bullet.js';
import {Hitbox} from './hitbox.js';

class Player {
    constructor(x, y, width, height, hitbox, speed, ship) {
        this.x = x;    
        this.y = y;    
        this.width = width;    
        this.height = height;    
        this.hitbox = hitbox;    
        this.speed = speed;    
        this.ship = ship;    
    }
    
    draw(ctx) {
        const {
            x,
            y,
            width,
            height,
            ship
        } = this;
        ctx.drawImage(ship.image, x, y);
    }
    
    shoot (gameObjects, player) {
        const {
            x,
            y
        } = player;
        console.log(player, x, y);
        const {
            ship
        } = player;
        const {
            cannon
        } = ship;
        console.log(ship.bulletWidth);
        /*ship.cannon.forEach((cannon) => {
            
        });*/
        for (let v = 0; v < ship.cannon.length;) {
            const bulletX = x + cannon[v];
            let yValue = v;
            yValue++;
            const bulletY = y + cannon[yValue];
            console.log('B', bulletY, y);
            console.log(cannon[v]);
            const newHitbox = new Hitbox(bulletX, bulletY, ship.bulletWidth, 45);
            const newBullet = new Bullet(bulletX, bulletY, ship.bulletWidth, 45, newHitbox, 25, 'Up', 'player');
            gameObjects.bullets.push(newBullet);
            v += 2;
            //[3, 45, 113, 45]
            console.log('D',v);
        }
    }
    
    move (direction) {
        const {
            speed,
            x
        } = this;
        
        if (direction === 'Left' && x > 0) {
            this.x -= speed; 
        } else if (direction === 'Right' && x + this.width < canWidth) {
            this.x += speed;
        }
    }
}

export class Player{