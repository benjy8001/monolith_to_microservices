import React, {Component, SyntheticEvent} from 'react';
import Wrapper from "../Wrapper";
import axios from "axios";
import {Role} from "../../classes/Role";
import {Navigate} from "react-router-dom";

class UserCreate extends Component<any, any> {
    state = {
        roles: [],
        redirect: false
    }
    first_name = '';
    last_name = '';
    email = '';
    role_id = 0;

    componentDidMount = async () => {
        const response = await axios.get(`roles`);
        this.setState({
            roles: response.data.data
        });
    }

    submit = async (e: SyntheticEvent) => {
        e.preventDefault();

        await axios.post('users', {
            first_name: this.first_name,
            last_name: this.last_name,
            email: this.email,
            role_id: this.role_id,
        });

        this.setState({
            redirect: true
        });
    }

    render() {
        if (this.state.redirect) {
            return <Navigate to={'/users'} />;
        }

        return (
            <Wrapper>
                <form onSubmit={this.submit}>
                    <div className="form-group">
                        <label>First Name</label>
                        <input type="text" className="form-control" name="first_name" required onChange={e => this.first_name = e.target.value} />
                    </div>
                    <div className="form-group">
                        <label>Last Name</label>
                        <input type="text" className="form-control" name="last_name" required onChange={e => this.last_name = e.target.value} />
                    </div>
                    <div className="form-group">
                        <label>Email</label>
                        <input type="email" className="form-control" name="email" required onChange={e => this.email = e.target.value} />
                    </div>
                    <div className="form-group">
                        <label>Role</label>
                        <select className="form-control" name="role_id" required onChange={e => this.role_id = parseInt(e.target.value)}>
                            <option>Select Role</option>
                            {this.state.roles.map(
                                (role: Role) => {
                                    return (
                                        <option key={role.id} value={role.id}>{role.name}</option>
                                    )
                                }
                            )}
                        </select>
                    </div>

                    <button className="btn btn-outline-secondary">Save</button>
                </form>
            </Wrapper>
        );
    }
}

export default UserCreate;