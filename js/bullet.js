class Bullet {
    constructor(x, y, width, height, hitbox, speed, direction, owner) {
        this.x = x;    
        this.y = y;    
        this.width = width;    
        this.height = height;    
        this.hitbox = hitbox;    
        this.speed = speed;    
        this.direction = direction;    
        this.owner = owner;    
        this.destroy = false;    
    }
    
    draw (ctx) {
        const {
            x,
            y,
            width,
            height
        } = this;
        ctx.fillRect(x, y, width, height);
    }
    
    move () {
        const {
            speed,
            direction
        } = this;
        
        if (direction === 'Up') {
            this.y -= speed;
            if (this.y < 0) {
                this.destroy = true;
            }
        } else if (direction === 'Down') {
            this.y += speed;
            if (this.y > 900) {
                this.destroy = true;
            }
        }
        
    }
}

export class Bullet{