import React, {Component, SyntheticEvent} from 'react';
import Wrapper from "../Wrapper";
import axios from "axios";
import {Permission} from "../../classes/Permission";
import {Navigate, useParams} from "react-router-dom";
import {Role} from "../../classes/Role";

class RoleEdit extends Component<any, any> {
    private selected: number[];
    private id: number;
    private name: string;

    constructor(props: any) {
        super(props);

        this.selected = [];
        this.id = 0;
        this.name = '';

        this.state = {
            redirect: false,
            permissions: [],
            selected: [],
            name: '',
        }
    }

    componentDidMount = async () => {
        this.id = this.props.params.id;
        const roleCall = await axios.get(`roles/${this.id}`);
        const role: Role = roleCall.data.data;
        this.selected = role.permissions.map((p: Permission) => p.id);

        const permissionsCall = await axios.get(`permissions`);
        this.setState({
            permissions: permissionsCall.data.data,
            name: role.name,
            selected: this.selected,
        });
    }

    check = (id: number) => {
        if (this.isCheck(id)) {
            this.selected = this.selected.filter(s => s !== id);
            return;
        }
        this.selected.push(id);
    }

    isCheck = (id: number) => {
        return this.state.selected.filter((s: number) => s === id).length > 0;
    }

    submit = async (e: SyntheticEvent) => {
        e.preventDefault();

        await axios.put(`roles/${this.id}`, {
            name: this.name,
            permissions: this.selected,
        });

        this.setState({
            redirect: true
        });
    }

    render() {
        if (this.state.redirect) {
            return <Navigate to={'/roles'} />;
        }

        return (
            <Wrapper>
                <form onSubmit={this.submit}>
                    <div className="form-group row">
                        <label htmlFor="name" className="col-sm-2 col-form-label">Name</label>
                        <div className="col-sm-10">
                            <input type="text" className="form-control" name="name" id="name"
                                   defaultValue={this.name = this.state.name}
                                   onChange={e => this.name = e.target.value} />
                        </div>
                    </div>

                    <div className="form-group row">
                        <label className="col-sm-2 col-form-label">Permissions</label>
                        <div className="col-sm-10">
                            {this.state.permissions.map(
                                (permission: Permission) => {
                                    return (
                                        <div className="form-check form-check-inline col-3" key={permission.id}>
                                            <input type="checkbox" className="form-check-input" value={permission.id}
                                                   defaultChecked={this.isCheck(permission.id)}
                                                   onChange={e => this.check(permission.id)} />
                                            <label className="form-check-label">{permission.name}</label>
                                        </div>
                                    )
                                }
                            )}
                        </div>
                    </div>

                    <button className="btn btn-outline-secondary">Save</button>
                </form>
            </Wrapper>
        );
    }
}

export default () => (
    <RoleEdit
        params={useParams()}
    />
);