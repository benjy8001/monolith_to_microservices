import React, {PropsWithChildren, useEffect, useState} from 'react';
import {connect} from "react-redux";
import {mapStateToProps} from "../redux/mapUser";
import {Link} from "react-router-dom";

const Header = (props: PropsWithChildren<any>) => {
    const [title, setTitle] = useState('Welcome');
    const [description, setDescription] = useState('Share links and earn 10% of the product price !');

    useEffect(() => {
        if (props.user?.id) {
            setTitle(props.user?.revenue + '€');
            setDescription('You have earned in total');
        }
    }, [props]);

    let buttons = (
        <p>
            <Link to={'/login'} className="btn btn-primary my-2">Login</Link>
            <Link to={'/register'} className="btn btn-secondary my-2">Register</Link>
        </p>);
    if (props.user?.id) {
        buttons = (
            <p>
                <Link to={'/stats'} className="btn btn-primary my-2">Stats</Link>
            </p>
        );
    }

    return (
        <section className="py-5 text-center container">
            <div className="row py-lg-5">
                <div className="col-lg-6 col-md-8 mx-auto">
                    <h1 className="fw-light">{title}</h1>
                    <p className="lead text-muted">{description}</p>
                    {buttons}
                </div>
            </div>
        </section>
    );
}

export default connect(mapStateToProps)(Header);