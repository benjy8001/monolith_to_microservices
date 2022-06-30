import React, {PropsWithChildren, useEffect} from 'react';
import Nav from "../components/Nav";
import axios from "axios";
import {User} from "../classes/User";
import {connect} from "react-redux";
import {mapDispatchToProps, mapStateToProps} from "../redux/mapUser";
import constants from "../constants";

const Wrapper = (props: PropsWithChildren<any>) => {

    useEffect(() => {
        try {
            (async () => {
                const response = await axios.get(`${constants.USERS_URL}/user`);
                const user: User = response.data;
                props.setUser(new User(
                    user.id,
                    user.first_name,
                    user.last_name,
                    user.email,
                    user.revenue
                ));
            })();
        } catch (e) {
            console.log(e);
            props.setUser(null);
        }
    }, []);

    return (
            <>
                <Nav />
                <main>
                    {props.children}
                </main>
            </>
        );
}


export default connect(mapStateToProps, mapDispatchToProps)(Wrapper);