import {User} from "../../classes/User";

const setUser = (user: User) => {
    return {
        type: 'SET_USER',
        user: user
    };
}

export default setUser;