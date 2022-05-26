import React, {Component, PropsWithRef, SyntheticEvent} from 'react';
import Wrapper from "../Wrapper";
import axios from "axios";
import {Role} from "../../classes/Role";
import {User} from "../../classes/User";
import { useParams } from 'react-router-dom';

class UserEdit extends Component<{ params: any }> {
    state = {
        roles: [],
        first_name: '',
        last_name: '',
        email: '',
        role_id: 0,
        redirect: false
    }
    id = 0;
    first_name = '';
    last_name = '';
    email = '';
    role_id = 0;

    componentDidMount = async () => {
        this.id = this.props.params.id;
        const rolesCall = await axios.get(`roles`);
        const userCall = await axios.get(`users/${this.id}`);
        const user: User = userCall.data.data;

        this.setState({
            roles: rolesCall.data.data,
            first_name: user.first_name,
            last_name: user.last_name,
            email: user.email,
            role_id: user.role.id,
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
        return (
            <Wrapper>
                <form onSubmit={this.submit}>
                    <div className="form-group">
                        <label>First Name</label>
                        <input type="text" className="form-control" name="first_name" required
                               defaultValue={this.state.first_name}
                               onChange={e => this.first_name = e.target.value} />
                    </div>
                    <div className="form-group">
                        <label>Last Name</label>
                        <input type="text" className="form-control" name="last_name" required
                               defaultValue={this.state.last_name}
                               onChange={e => this.last_name = e.target.value} />
                    </div>
                    <div className="form-group">
                        <label>Email</label>
                        <input type="email" className="form-control" name="email" required
                               defaultValue={this.state.email}
                               onChange={e => this.email = e.target.value} />
                    </div>
                    <div className="form-group">
                        <label>Role</label>
                        <select className="form-control" name="role_id" required
                                value={this.state.role_id}
                                onChange={e => {
                                    this.role_id = parseInt(e.target.value);
                                    this.setState({
                                        role_id: this.role_id
                                    });
                                }}>
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

//export default UserEdit;

export default () => (
    <UserEdit
        params={useParams()}
    />
);