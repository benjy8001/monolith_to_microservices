import React, {Component} from 'react';
import Wrapper from "../Wrapper";
import axios from "axios";
import {Link} from "react-router-dom";
import {Role} from "../../classes/Role";

class Roles extends Component<any, any> {
    constructor(props: any) {
        super(props);

        this.state = {
            roles: [],
        }
    }

    componentDidMount = async () => {
        const rolesCall = await axios.get(`roles`);
        this.setState({
            roles: rolesCall.data.data,
        });
    }

    delete = async (id: number) => {
        if (window.confirm('Are you sure ?')) {
            await axios.delete(`roles/${id}`);
            this.setState({
                roles: this.state.roles.filter((r: Role) => r.id !== id)
            });
        }
    }

    render() {
        return (
            <Wrapper>
                <div className="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 col-md-3 border-bottom">
                    <div className="btn-toolbar mb-2 mb-md-0">
                        <Link to={'/roles/create'} className="btn btn-sm btn-outline-secondary">Add</Link>
                    </div>
                </div>

                <h2>Roles list</h2>
                <div className="table-responsive">
                    <table className="table table-striped table-sm">
                        <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Name</th>
                            <th scope="col">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        {this.state.roles.map(
                            (role: Role) => {
                                return (
                                    <tr>
                                        <td>{role.id}</td>
                                        <td>{role.name}</td>
                                        <td>
                                            <div className="btn-group mr-2">
                                                <Link to={`/roles/${role.id}/edit`} className="btn btn-sm btn-outline-secondary">Edit</Link>
                                                <a href="#" className="btn btn-sm btn-outline-secondary" onClick={() => this.delete(role.id)}>Delete</a>
                                            </div>
                                        </td>
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

export default Roles;