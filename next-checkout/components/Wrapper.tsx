import React, {Component} from 'react';
import Head from "next/head";

const Wrapper = (props) => {
    return (
        <div>
            <Head>
                <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet"
                      integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor"
                      crossOrigin="anonymous"/>
            </Head>
            <div className="container">
                <main>
                    {props.children}
                </main>
            </div>
        </div>
    );
}

export default Wrapper;