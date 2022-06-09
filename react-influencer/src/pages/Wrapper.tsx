import React, {PropsWithChildren} from 'react';
import Nav from "../components/Nav";
import Header from "../components/Header";

const Wrapper = (props: PropsWithChildren<any>) => {
    return (
            <>
                <Nav />
                <main>
                    <Header />
                    <div className="album py-5 bg-light">
                        <div className="container">
                            {props.children}
                        </div>
                    </div>
                </main>
            </>
        );
}


export default Wrapper;