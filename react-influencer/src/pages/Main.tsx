import React, {useEffect, useState} from 'react';
import Wrapper from "./Wrapper";
import axios from "axios";
import {Product} from "../classes/Product";
import Header from "../components/Header";

const Main = () => {
    const [products, setProducts] = useState([]);
    const [searchText, setSearchText] = useState('');

    useEffect(() => {
        (
            async () => {
                const response = await axios.get(`products?s=${searchText}`);
                setProducts(response.data.data);
            }
        )();
    }, [searchText]);

    return (
        <Wrapper>
            <Header />
            <div className="album py-5 bg-light">
                <div className="container">
                    <div className="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
                        <div className="col-md-12 mb-4 input-group">
                            <input type="text" className="form-control" placeholder="Search"
                                   onKeyUp={e => setSearchText((e.target as HTMLInputElement).value)} />
                        </div>
                        {products.map((product: Product) => {
                            return (
                                <div className="col" key={product.id}>
                                    <div className="card shadow-sm">
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