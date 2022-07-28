class Explosion {
    constructor(x, y, explosionFrames, width) {
        this.x = x;
        this.y = y;
        this.width = width;
        this.height = width;
        this.frameCounter = 0;
        this.frames = explosionFrames;
    }
    
    draw(ctx) {
        const {
            x,
            y,
            width,
            height,
            frames,
            frameCounter
        } = this;
        
        ctx.drawImage(frames, 92 * frameCounter, 0, 92, 92, x, y, width, height);
    }
}

function animateExplosion(gameObjects) {
    gameObjects.explosions.forEach((explosion, id)=>{
        explosion.frameCounter++;
        if (explosion.frameCounter === 11) {
            gameObjects.explosions.splice(id, 1);   
        }        
    });  
}

export {Explosion, animateExplosion};