import React, {Component, SyntheticEvent} from "react";
import './Public.css';
import axios from "axios";
import {Navigate} from "react-router-dom";

class Login extends Component<any, any> {
    private email: string;
    private password: string;

    constructor(props: any) {
        super(props);

        this.state = {
            redirect: false
        }
        this.email = '';
        this.password = '';
    }

    submit = async (e: SyntheticEvent) => {
        e.preventDefault();

       await axios.post('login', {
            email: this.email,
            password: this.password,
            scope: 'influencer',
        });

        this.setState({
            redirect: true
        });
    }

    render() {
        if (this.state.redirect) {
            return <Navigate to={'/'} />;
        }

        return (
            <main className="form-signin w-100 m-auto">
                <form onSubmit={this.submit}>
                        <h1 className="h3 mb-3 fw-normal">Please sign in</h1>

                        <div className="form-floating">
                            <input type="email" className="form-control" id="floatingInput" placeholder="name@example.com" onChange={e => this.email = e.target.value} />
                                <label htmlFor="floatingInput">Email address</label>
                        </div>
                        <div className="form-floating">
                            <input type="password" className="form-control" id="floatingPassword" placeholder="Password" onChange={e => this.password = e.target.value} />
                                <label htmlFor="floatingPassword">Password</label>
                        </div>
                        <button className="w-100 btn btn-lg btn-primary" type="submit">Sign in</button>
                </form>
            </main>
        )
    }
}

export default Login;