import React, {useEffect, useState} from 'react';
import Wrapper from "./Wrapper";
import axios from "axios";
import {Product} from "../classes/Product";
import Header from "../components/Header";
import constants from "../constants";

const Main = () => {
    const [products, setProducts] = useState([]);
    const [searchText, setSearchText] = useState('');
    const [selected, setSelected] = useState([]);
    const [notify, setNotify] = useState({
        show: false,
        error: false,
        message: ''
    });

    useEffect(() => {
        (
            async () => {
                const response = await axios.get(`products?s=${searchText}`);
                setProducts(response.data.data);
            }
        )();
    }, [searchText]);

    const isSelected = (id: number) => selected.filter(s => s === id).length > 0;
    const select = (id: number) => {
        if (isSelected(id)) {
            setSelected(selected.filter(s => s !== id));
            return;
        }
        // @ts-ignore
        setSelected([...selected, id]);
    }

    const generate = async () => {
        try {
            const response = await axios.post('links', {
                products: selected
            });

            setNotify({
                show: true,
                error: false,
                message: `Link generated: ${constants.CHECKOUT_URL}/${response.data.data.code}`
            });
        } catch (e) {
            setNotify({
                show: true,
                error: true,
                message: 'You should be logged in to generate a link!'
            });
        } finally {
            setTimeout(() => {
                setNotify({
                    show: false,
                    error: false,
                    message: ''
                });
            }, 2000);
        }
    }

    let button, info;
    if (selected.length > 0) {
        button = (
            <div className="input-group-append">
                <button className="btn btn-info" onClick={generate}>Generate Link</button>
            </div>
        );
    }
    if (notify.show) {
        info = (
            <div className="col-md-12 md-4">
                <div className={notify.error ? "alert alert-danger": "alert alert-info"} role="alert">
                    {notify.message}
                </div>
            </div>
        );
    }

    return (
        <Wrapper>
            <Header />
            <div className="album py-5 bg-light">
                <div className="container">
                    <div className="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
                        {info}

                        <div className="col-md-12 mb-4 input-group">
                            <input type="text" className="form-control" placeholder="Search"
                                   onKeyUp={e => setSearchText((e.target as HTMLInputElement).value)} />
                            {button}
                        </div>
                        {products.map((product: Product) => {
                            return (
                                <div className="col" key={product.id}>
                                    <div className={isSelected(product.id) ? "card shadow-sm selected" : "card shadow-sm"}
                                    onClick={() => select(product.id)} >
                                        <img src={product.image} height="200" />
                                        <div className="card-body">
                                            <p className="card-text">{product.title}</p>
                                            <div className="d-flex justify-content-between align-items-center">
                                                <small className="text-muted">{product.price}€</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            );
                        })}
                    </div>
                </div>
            </div>

        </Wrapper>
    );
}

export default Main;