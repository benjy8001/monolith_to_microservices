import React from 'react';
import {Link} from "react-router-dom";

const Nav = () => {
    return (
        <header>
            <div className="navbar border-bottom shadow-sm">
                <div className="container">
                    <Link to={'/'} className="navbar-brand my-0 mr-md-auto font-weight-normal">Influencer</Link>
                    <Link to={'/login'} className="btn btn-outline-primary">Login</Link>
                </div>
            </div>
        </header>
    );
}

export default Nav;