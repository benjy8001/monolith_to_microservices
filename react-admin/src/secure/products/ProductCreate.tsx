import React, {Component, SyntheticEvent} from 'react';
import Wrapper from "../Wrapper";
import {Navigate} from "react-router-dom";
import axios from "axios";
import ImageUpload from "../components/ImageUpload";

class ProductCreate extends Component<any, any> {
    private title: string;
    private description: string;
    private image: string;
    private price: number;

    constructor(props: any) {
        super(props);

        this.title = '';
        this.description = '';
        this.image = '';
        this.price = 0;

        this.state = {
            image: '',
            redirect: false,
        }
    }

    submit = async (e: SyntheticEvent) => {
        e.preventDefault();

        await axios.post(`products`, {
            title: this.title,
            description: this.description,
            image: this.image,
            price: this.price,
        });

        this.setState({
            redirect: true
        });
    }

    imageChangeHandler = (image: string) => {
        this.image = image;
        this.setState({
            image: this.image,
        })
    }

    render() {
        if (this.state.redirect) {
            return <Navigate to={'/products'} />;
        }

        return (
            <Wrapper>
                <form onSubmit={this.submit}>
                    <div className="form-group">
                        <label>Title</label>
                        <input type="text" className="form-control" name="title" onChange={e => this.title = e.target.value} />
                    </div>
                    <div className="form-group">
                        <label>Description</label>
                        <textarea className="form-control" name="description" onChange={e => this.description = e.target.value}></textarea>
                    </div>
                    <div className="form-group">
                        <label>Image</label>
                        <ImageUpload value={this.image = this.state.image} imageChangeHandler={this.imageChangeHandler} />
                    </div>
                    <div className="form-group">
                        <label>Price</label>
                        <input type="number" className="form-control" name="price" onChange={e => this.price = parseFloat(e.target.value)} />
                    </div>

                    <button className="btn btn-outline-secondary">Save</button>
                </form>
            </Wrapper>
        );
    }
}

export default ProductCreate;