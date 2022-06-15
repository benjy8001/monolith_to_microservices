import React, {Component} from 'react';
import Wrapper from "../Wrapper";
import axios from "axios";
import {Link, useParams} from "react-router-dom";
import {Order} from "../../classes/Order";
import {OrderItem} from "../../classes/OrderItem";
import constants from "../../constants";

class OrderItems extends Component<any, any> {
    private id: number;

    constructor(props: any) {
        super(props);

        this.id = 0;
        this.state = {
            order_items: [],
        }
    }

    componentDidMount = async () => {
        this.id = this.props.params.id;
        const orderCall = await axios.get(`${constants.BASE_URL}/orders/${this.id}`);
        const order: Order = orderCall.data.data;
        this.setState({
            order_items: order.order_items,
        });
    }

    render() {
        return (
            <Wrapper><div className="table-responsive">
                <table className="table table-striped table-sm">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Product Title</th>
                        <th scope="col">Price</th>
                        <th scope="col">Quantity</th>
                    </tr>
                    </thead>
                    <tbody>
                    {this.state.order_items.map(
                        (order_item: OrderItem) => {
                            return (
                                <tr key={order_item.id}>
                                    <td>{order_item.id}</td>
                                    <td>{order_item.product_title}</td>
                                    <td>{order_item.price}</td>
                                    <td>{order_item.quantity}</td>
                                </tr>
                            )
                        }
                    )}
                    </tbody>
                </table>
            </div>
            </Wrapper>
        );
    }
}

export default () => (
    <OrderItems
        params={useParams()}
    />
);