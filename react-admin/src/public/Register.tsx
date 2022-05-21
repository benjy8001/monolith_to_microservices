import React, {Component, SyntheticEvent} from 'react';
import axios from 'axios';
import './Public.css';

class Register extends Component {
    first_name = '';
    last_name = '';
    email = '';
    password = '';
    password_confirm = '';

    submit = async (e: SyntheticEvent) => {
        e.preventDefault();

        const response = await axios.post('https://backend.micro.test/api/register', {
            first_name: this.first_name,
            last_name: this.last_name,
            email: this.email,
            password: this.password,
            password_confirm: this.password_confirm,
        });
        console.log(response);
    }

    render() {
        return (
            <main className="form-register w-100 m-auto">
                <form onSubmit={this.submit}>
                    <h1 className="h3 mb-3 fw-normal">Please register</h1>
                    <div className="form-floating">
                        <input type="text" className="form-control" id="firstName" placeholder="First name" required onChange={e => this.first_name = e.target.value} />
                        <label htmlFor="firstName">First name</label>
                    </div>
                    <div className="form-floating">
                        <input type="text" className="form-control" id="firstLast" placeholder="Last name" required onChange={e => this.last_name = e.target.value} />
                        <label htmlFor="firstLast">Last name</label>
                    </div>
                    <div className="form-floating">
                        <input type="email" className="form-control" id="floatingInput" placeholder="name@example.com" required onChange={e => this.email = e.target.value} />
                        <label htmlFor="floatingInput">Email address</label>
                    </div>
                    <div className="form-floating">
                        <input type="password" className="form-control" id="floatingPassword" placeholder="Password" required onChange={e => this.password = e.target.value} />
                        <label htmlFor="floatingPassword">Password</label>
                    </div>
                    <div className="form-floating">
                        <input type="password" className="form-control" id="passwordConfirm" placeholder="Password confirm" required onChange={e => this.password_confirm = e.target.value} />
                        <label htmlFor="passwordConfirm">Password confirm</label>
                    </div>
                    <button className="w-100 btn btn-lg btn-primary mt-3" type="submit">Register</button>
                </form>
            </main>
        );
    }
}

export default Register;