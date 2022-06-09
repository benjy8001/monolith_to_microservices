import {User} from "../classes/User";
import {Dispatch} from "react";
import setUser from "./actions/setUserAction";

export const mapStateToProps = (state: {user: User}) => {
    return {
        user: state.user
    };
}

export const mapDispatchToProps = (dispatch: Dispatch<any>) => {
    return {
        setUser: (user: User) => dispatch(setUser(user))
    }
}