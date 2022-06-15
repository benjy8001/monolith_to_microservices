import React, {Component} from 'react';
import Wrapper from "../Wrapper";
import axios from "axios";
import {Link} from "react-router-dom";
import {Order} from "../../classes/Order";
import Paginator from "../components/Paginator";
import constants from "../../constants";

class Orders extends Component<any, any> {
    private page: number;
    private last_page: number;

    constructor(props: any) {
        super(props);

        this.page = 1;
        this.last_page = 0
        this.state = {
            orders: [],
        }
    }

    componentDidMount = async () => {
        const ordersCall = await axios.get(`${constants.BASE_URL}/orders?page=${this.page}`);
        this.setState({
            orders: ordersCall.data.data,
        });
        this.last_page = ordersCall.data.meta.last_page;
    }

    handlePageChange = async (page: number) => {
        this.page = page;
        await this.componentDidMount();
    }

    handlerExport = async () => {
        const reponse = await axios.get(`${constants.BASE_URL}/export`, {responseType: 'blob'});
        //const blob = new Blob([reponse.data], {type: 'text/csv'});
        const downloadUrl = window.URL.createObjectURL(reponse.data);
        const link = document.createElement('a');
        link.href = downloadUrl;
        link.download = 'orders.csv';
        link.click();
    }

    render() {
        return (
            <Wrapper>
                <div className="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 col-md-3 border-bottom">
                    <div className="btn-toolbar mb-2 mb-md-0">
                        <a onClick={this.handlerExport} className="btn btn-sm btn-outline-secondary">Export</a>
                    </div>
                </div>

                <h2>Orders list</h2>
                <div className="table-responsive">
                    <table className="table table-striped table-sm">
                        <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Name</th>
                            <th scope="col">Email</th>
                            <th scope="col">Total</th>
                            <th scope="col">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        {this.state.orders.map(
                            (order: Order) => {
                                return (
                                    <tr key={order.id}>
                                        <td>{order.id}</td>
                                        <td>{order.first_name} {order.last_name}</td>
                                        <td>{order.email}</td>
                                        <td>{order.total}</td>
                                        <td>
                                            <div className="btn-group mr-2">
                                                <Link to={`/orders/${order.id}`} className="btn btn-sm btn-outline-secondary">View</Link>
                                            </div>
                                        </td>
                                    </tr>
                                )
                            }
                        )}
                        </tbody>
                    </table>
                </div>

                <Paginator lastPage={this.last_page} handlePageChange={this.handlePageChange} />
            </Wrapper>
        );
    }
}

export default Orders;