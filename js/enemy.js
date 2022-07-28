import {Bullet} from './bullet.js';
import {Hitbox} from './hitbox.js';

class Enemy {
    constructor(x, y, width, height, hitbox, speed, shooting, ship) {
        this.x = x;    
        this.y = y;    
        this.width = width;    
        this.height = height;    
        this.hitbox = hitbox;    
        this.speed = speed;    
        this.shooting = shooting;    
        this.reload = 500;    
        this.ship = ship;    
        this.destroy = false;
        this.destroyAnimation = true;
    }
    
    draw(ctx, can) {
        const {
            x,
            y,
            width,
            height,
            ship
        } = this;
        drawImageRot(ctx, ship.image, x, y, ship.width, ship.height, 180);
    }
    
    die(gameObjects) {
        const {
            destroy,
            destroyAnimation,
            ship
        } = this;
        if (destroy) {
            if (destroyAnimation) {
                let width;
                if (enemy.ship.height > enemy.ship.width) {
                    width = enemy.ship.width - enemy.ship.width * 12 / 100;
                } else {
                    width = enemy.ship.height - enemy.ship.height * 12 / 100;
                }
                gameObjects.explosions.push(new Explosion(enemy.x, enemy.y, explosionFrames, width));
            }
        }
    }
    
    shoot (gameObjects) {
        const {
            x,
            y
        } = this;
        const {
            ship
        } = this;
        ship.cannon.forEach((cannon) => {
            const newHitbox = new Hitbox(x + cannon, y, ship.bulletWidth, 45);
            const newBullet = new Bullet(x + cannon, y, ship.bulletWidth, 45, newHitbox, 25, 'Down', 'enemy');
            gameObjects.bullets.push(newBullet);
        });
    }
    
    move () {
        const {
            speed
        } = this;
        
        this.y += speed;
        if (this.y > 900) {
            this.destroy = true; 
            this.destroyAnimation = false;
        }
    }
}

function drawImageRot(ctx, img, x, y, width, height, deg) {
    ctx.save()

    const rad = deg * Math.PI / 180;

    ctx.translate(x + width / 2, y + height / 2);

    ctx.rotate(rad);

    ctx.drawImage(img, width / 2 * (-1), height / 2 * (-1), width, height);

    ctx.restore();
}
export {Enemy};