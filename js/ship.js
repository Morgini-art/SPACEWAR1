class Ship {
    constructor(image, owner, width, height, cannon, bulletWidth) {
        this.image = new Image();
        this.width = width;
        this.height = height;
        this.owner = owner;  
        this.image.src = image;
        this.cannon = cannon;
        this.bulletWidth = bulletWidth;
    }
}


export class Ship{