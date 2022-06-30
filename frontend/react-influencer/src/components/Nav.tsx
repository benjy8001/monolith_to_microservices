import React, {PropsWithChildren} from 'react';
import {Link} from "react-router-dom";
import {connect} from "react-redux";
import {mapStateToProps} from "../redux/mapUser";
import axios from "axios";
import constants from "../constants";

const Nav = (props: PropsWithChildren<any>) => {
    let menu= (
        <Link to={'/login'} className="btn btn-outline-primary">Login</Link>
    );


    const logout = async () => {
        await axios.post(`${constants.USERS_URL}/logout`, {});
        localStorage.clear();
        axios.defaults.headers.common['Authorization'] = '';
    }

    if (props.user?.id) {
        menu = (
            <>
                <div className="nav-item text-nowrap d-flex">
                    <Link to={'/stats'} className="p-2 text-dark">Stats</Link>
                    <Link to={'/rankings'} className="p-2 text-dark">Rankings</Link>
                    <Link to={'/login'} onClick={logout} className="p-2 text-dark">Logout</Link>
                    <Link to={'/profile'} className="btn btn-outline-primary">{props.user.first_name}</Link>
                </div>
            </>
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