export class Product {
    id: number;
    image: string;
    title: string;
    description: string;
    price: number;

    constructor(id = 0, image = '', title = '', description = '', price = 0) {
        this.id = id;
        this.image = image;
        this.title = title;
        this.description = description;
        this.price = price;
    }
}