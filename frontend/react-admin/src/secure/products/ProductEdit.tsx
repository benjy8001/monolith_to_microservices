import React, {Component, SyntheticEvent} from 'react';
import Wrapper from "../Wrapper";
import {Navigate, useParams} from "react-router-dom";
import axios from "axios";
import ImageUpload from "../components/ImageUpload";
import {Product} from "../../classes/Product";

class ProductEdit extends Component<any, any> {
    private id: number;

    constructor(props: any) {
        super(props);

        this.id = 0;

        this.state = {
            title: '',
            description: '',
            image: '',
            price: 0,
            redirect: false,
        }
    }

    componentDidMount = async () => {
        //this.id = this.props.match.params.id;
        this.id = this.props.params.id;
        const productCall = await axios.get(`products/${this.id}`);
        const product: Product = productCall.data.data;
        this.setState({
            title: product.title,
            description: product.description,
            image: product.image,
            price: product.price,
        });
    }

    submit = async (e: SyntheticEvent) => {
        e.preventDefault();

        await axios.put(`products/${this.id}`, {
            title: this.state.title,
            description: this.state.description,
            image: this.state.image,
            price: this.state.price,
        });

        this.setState({
            redirect: true
        });
    }

    imageChangeHandler = (image: string) => {
        this.setState({
            image: image,
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
                        <input type="text" className="form-control" name="title"
                               defaultValue={this.state.title}
                               onChange={e => this.setState({title: e.target.value})} />
                    </div>
                    <div className="form-group">
                        <label>Description</label>
                        <textarea className="form-control" name="description"
                                  defaultValue={this.state.description}
                                  onChange={e => this.setState({description: e.target.value})}></textarea>
                    </div>
                    <div className="form-group">
                        <label>Image</label>
                        <ImageUpload value={this.state.image} imageChangeHandler={this.imageChangeHandler} />
                    </div>
                    <div className="form-group">
                        <label>Price</label>
                        <input type="number" className="form-control" name="price"
                               value={this.state.price}
                               onChange={e => {
                                   this.setState({
                                       price: parseFloat(e.target.value)
                                   });
                               }} />
                    </div>

                    <button className="btn btn-outline-secondary">Save</button>
                </form>
            </Wrapper>
        );
    }
}

export default () => (
    <ProductEdit
        params={useParams()}
    />
);