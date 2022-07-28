class Hitbox {
    constructor(x, y, width, height) {
        this.x = x;
        this.y = y;
        this.width = width;
        this.height = height;
    }
}

function checkCollisionWith(hitbox1, hitbox2) {
    if (hitbox1.x < hitbox2.x + hitbox2.width &&
        hitbox1.x + hitbox1.width > hitbox2.x &&
        hitbox1.y < hitbox2.y + hitbox2.height &&
        hitbox1.height + hitbox1.y > hitbox2.y) {
        //console.log('Kolizja pomiędzy '+hitbox1+' a '+hitbox2);
        
        return true;

    } else {
        return false;
    }
}
export {Hitbox, checkCollisionWith};