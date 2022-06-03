import React, {Component} from 'react';
import axios from "axios";
import {Link, Navigate} from 'react-router-dom';
import {connect} from "react-redux";

class Nav extends Component<any, any> {
    constructor(props: any) {
        super(props);

        this.state = {
            redirect: false
        }
    }

    handleClick = () => {
        localStorage.clear();
        axios.defaults.headers.common['Authorization'] = '';
        this.setState({
            redirect: true
        });
    }

    render() {
        if (this.state.redirect) {
            return <Navigate to={'/login'} />;
        }

        return (
            <header className="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0 shadow">
                <a className="navbar-brand col-md-3 col-lg-2 me-0 px-3 fs-6" href="#">Company name</a>
                <button className="navbar-toggler position-absolute d-md-none collapsed" type="button" data-bs-toggle="collapse"
                        data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false"
                        aria-label="Toggle navigation">
                    <span className="navbar-toggler-icon"></span>
                </button>
                <div className="navbar-nav">
                    <div className="nav-item text-nowrap">
                        <Link to={'/profile'} className="p-2 text-white text-decoration-none">{this.props.user.name}</Link>
                        <a className="p-2 text-white text-decoration-none" href="#" onClick={this.handleClick}>Sign out</a>
                    </div>
                </div>
            </header>
        );
    }
}

// @ts-ignore
export default connect(state => ({user: state.user}))(Nav);