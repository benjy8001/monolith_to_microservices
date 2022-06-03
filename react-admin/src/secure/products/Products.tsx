import React, {Component} from 'react';
import Wrapper from "../Wrapper";
import {Link} from "react-router-dom";
import axios from "axios";
import {Product} from "../../classes/Product";
import Paginator from "../components/Paginator";
import Deleter from "../components/Deleter";
import {connect} from "react-redux";
import {mapStateToProps} from "../../redux/mapUser";
import {User} from "../../classes/User";

class Products extends Component<{ user: User }, any> {
    private page: number;
    private last_page: number;

    constructor(props: any) {
        super(props);

        this.page = 1;
        this.last_page = 0
        this.state = {
            products: [],
        }
    }

    componentDidMount = async () => {
        const productsCall = await axios.get(`products?page=${this.page}`);
        this.setState({
            products: productsCall.data.data,
        });
        this.last_page = productsCall.data.meta.last_page;
    }

    handlePageChange = async (page: number) => {
        this.page = page;
        await this.componentDidMount();
    }

    handleDelete = async (id: number) => {
        this.setState({
            products: this.state.products.filter((p: Product) => p.id !== id)
        });
    }

    action = (id: number) => {
        if (this.props.user.canEdit('products')) {
            return (
                <div className="btn-group mr-2">
                    <Link to={`/products/${id}/edit`} className="btn btn-sm btn-outline-secondary">Edit</Link>
                    <Deleter id={id} endpoint={'products'} handleDelete={this.handleDelete} />
                </div>
            );
        }
    }

    render() {
        let addButton = null;
        if (this.props.user.canView('products')) {
            addButton = (
                <div className="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 col-md-3 border-bottom">
                    <div className="btn-toolbar mb-2 mb-md-0">
                        <Link to={'/products/create'} className="btn btn-sm btn-outline-secondary">Add</Link>
                    </div>
                </div>
            );
        }

        return (
            <Wrapper>
                {addButton}

                <h2>Roles list</h2>
                <div className="table-responsive">
                    <table className="table table-striped table-sm">
                        <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Image</th>
                            <th scope="col">Title</th>
                            <th scope="col">Description</th>
                            <th scope="col">Price</th>
                            <th scope="col">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        {this.state.products.map(
                            (product: Product) => {
                                return (
                                    <tr key={product.id}>
                                        <td>{product.id}</td>
                                        <td><img src={product.image} width="50" /></td>
                                        <td>{product.title}</td>
                                        <td>{product.description}</td>
                                        <td>{product.price}</td>
                                        <td>
                                            {this.action(product.id)}
                                        </td>
                                    </tr>
                                )
                            }
                        )}
                        </tbody>
                    </table>
                </div>

                <Paginator lastPage={this.last_page} handlePageChange={this.handlePageChange}/>
            </Wrapper>
        );
    }
}

export default connect(mapStateToProps)(Products);