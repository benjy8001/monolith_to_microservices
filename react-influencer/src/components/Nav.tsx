import React, {PropsWithChildren} from 'react';
import {Link} from "react-router-dom";
import {connect} from "react-redux";
import {mapStateToProps} from "../redux/mapUser";

const Nav = (props: PropsWithChildren<any>) => {
    let menu;

    if (props.user) {
        menu = (
            <>
                <div className="nav-item text-nowrap d-flex">
                    <Link to={'/login'} onClick={() => localStorage.clear()} className="p-2 text-dark">Logout</Link>
                    <Link to={'/profile'} className="btn btn-outline-primary">{props.user.first_name}</Link>
                </div>
            </>
        );
    } else {
        menu = (
            <Link to={'/login'} className="btn btn-outline-primary">Login</Link>
        );
    }

    return (
        <header>
            <div className="navbar border-bottom shadow-sm">
                <div className="container">
                    <Link to={'/'} className="navbar-brand my-0 mr-md-auto font-weight-normal">Influencer</Link>

                    {menu}
                </div>
            </div>
        </header>
    );
}

export default connect(mapStateToProps)(Nav);