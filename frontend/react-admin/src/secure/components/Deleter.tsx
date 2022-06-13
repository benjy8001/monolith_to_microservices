import React, {Component} from 'react';
import axios from "axios";

class Deleter extends Component<{id: number, endpoint: string, handleDelete: any}, any> {
    constructor(props: any) {
        super(props);
    }

    delete = async () => {
        if (window.confirm('Are you sure ?')) {
            await axios.delete(`${this.props.endpoint}/${this.props.id}`);

            this.props.handleDelete(this.props.id);
        }
    }

    render() {
        return (
                <a href="#" className="btn btn-sm btn-outline-secondary" onClick={() => this.delete()}>Delete</a>
        );
    }
}

export default Deleter;