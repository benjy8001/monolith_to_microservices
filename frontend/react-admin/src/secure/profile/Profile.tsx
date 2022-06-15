import React, {Component, SyntheticEvent} from 'react';
import Wrapper from "../Wrapper";
import axios from "axios";
import {User} from "../../classes/User";
import {connect} from "react-redux";
import {mapDispatchToProps, mapStateToProps} from "../../redux/mapUser";
import constants from "../../constants";

class Profile extends Component<any, any> {
    private first_name: string;
    private last_name: string;
    private email: string;
    private password: string;
    private password_confirm: string;

    constructor(props: any) {
        super(props);

        this.first_name = '';
        this.last_name = '';
        this.email = '';
        this.password = '';
        this.password_confirm = '';

        this.state = {
            first_name: '',
            last_name: '',
            email: '',
        }
    }

    updateInfo = async (e: SyntheticEvent) => {
        e.preventDefault();

        const response = await axios.put(`${constants.BASE_URL}/users/info`, {
            first_name: this.first_name,
            last_name: this.last_name,
            email: this.email,
        });

        const user: User = response.data;
        this.props.setUser(new User(
            user.id,
            user.first_name,
            user.last_name,
            user.email,
            user.role,
            user.permissions
        ));
    }

    updatePassword = async (e: SyntheticEvent) => {
        e.preventDefault();

        await axios.put(`${constants.USERS_URL}/users/password`, {
            password: this.password,
            password_confirm: this.password_confirm,
        });
    }

    render() {
        return (
            <Wrapper>
                <h2>Account informations</h2>
                <hr />
                <form onSubmit={this.updateInfo}>
                    <div className="form-group">
                        <label>First Name</label>
                        <input type="text" className="form-control" name="first_name" id="first_name"
                               defaultValue={this.first_name = this.props.user.first_name}
                               onChange={e => this.first_name = e.target.value}
                        />
                    </div>
                    <div className="form-group">
                        <label>Last Name</label>
                        <input type="text" className="form-control" name="last_name" id="last_name"
                               defaultValue={this.last_name = this.props.user.last_name}
                               onChange={e => this.first_name = e.target.value}
                        />
                    </div>
                    <div className="form-group">
                        <label>Email</label>
                        <input type="email" className="form-control" name="email" id="email"
                               defaultValue={this.email = this.props.user.email}
                               onChange={e => this.email = e.target.value}
                        />
                    </div>

                    <button className="btn btn-outline-secondary">Save</button>
                </form>

                <h2 className="mt-4">Change Password</h2>
                <hr />
                <form onSubmit={this.updatePassword}>
                    <div className="form-group">
                        <label>Password</label>
                        <input type="password" className="form-control" name="password" id="password"
                               onChange={e => this.password = e.target.value}
                        />
                    </div>
                    <div className="form-group">
                        <label>Password Confirm</label>
                        <input type="password" className="form-control" name="password_confirm" id="password_confirm"
                               onChange={e => this.password_confirm = e.target.value}
                        />
                    </div>
                    <button className="btn btn-outline-secondary">Save</button>
                </form>
            </Wrapper>
        );
    }
}

export default connect(mapStateToProps, mapDispatchToProps)(Profile);