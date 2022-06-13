import Wrapper from "../components/Wrapper";
import {useRouter} from "next/router";
import {useEffect, useState} from "react";
import axios from "axios";
import constants from "../constants";

const Home = () => {
    const router = useRouter();
    const {code} = router.query;
    const [user, setUser] = useState(null);
    const [products, setProducts] = useState([]);

    useEffect(() => {
        if (undefined !== code) {
            (
                async () => {
                    const response = await axios.get(`${constants.BASE_URL}links/${code}`);
                    const data = response.data.data;

                    setUser(data.user);
                    setProducts(data.products);
                }
            )();
        }
    }, [code]);

    return (
      <Wrapper>
          <div className="py-5 text-center">
                  <h2>Welcome</h2>
                  <p className="lead">{user?.first_name} {user?.last_name} has invited you to buy this item(s).</p>
          </div>

          <div className="row g-5">
              <div className="col-md-5 col-lg-4 order-md-last">
                  <h4 className="d-flex justify-content-between align-items-center mb-3">
                      <span className="text-primary">Products</span>
                      <span className="badge bg-primary rounded-pill">X</span>
                  </h4>
                  <ul className="list-group mb-3">
                      {products.map(p => {
                          return (
                              <li className="list-group-item d-flex justify-content-between lh-sm" key={p.id}>
                                  <div>
                                      <h6 className="my-0">{p.title}</h6>
                                      <small className="text-muted">{p.description}</small>
                                  </div>
                                  <span className="text-muted">{p.price}€</span>
                              </li>
                          );
                      })}
                      <li className="list-group-item d-flex justify-content-between">
                          <span>Total (USD)</span>
                          <strong>$20</strong>
                      </li>
                  </ul>
              </div>
              <div className="col-md-7 col-lg-8">
                  <h4 className="mb-3">Payment Info</h4>
                  <form className="needs-validation" noValidate>
                      <div className="row g-3">
                          <div className="col-sm-6">
                              <label htmlFor="firstName" className="form-label">First name</label>
                              <input type="text" className="form-control" id="firstName" placeholder="First name" value=""
                                     required />
                          </div>

                          <div className="col-sm-6">
                              <label htmlFor="lastName" className="form-label">Last name</label>
                              <input type="text" className="form-control" id="lastName" placeholder="Last name" value=""
                                     required />
                          </div>

                          <div className="col-12">
                              <label htmlFor="email" className="form-label">Email</label>
                              <input type="email" className="form-control" id="email" placeholder="you@example.com" required />
                          </div>

                          <div className="col-12">
                              <label htmlFor="address" className="form-label">Address</label>
                              <input type="text" className="form-control" id="address" placeholder="1234 Main St"
                                     required />
                          </div>

                          <div className="col-12">
                              <label htmlFor="address2" className="form-label">Address 2 <span
                                  className="text-muted">(Optional)</span></label>
                              <input type="text" className="form-control" id="address2"
                                     placeholder="Apartment or suite" />
                          </div>

                          <div className="col-md-5">
                              <label htmlFor="country" className="form-label">Country</label>
                              <input type="text" className="form-control" id="country" placeholder="France" />
                          </div>

                          <div className="col-md-4">
                              <label htmlFor="city" className="form-label">City</label>
                              <input type="text" className="form-control" id="city" placeholder="City" />
                          </div>

                          <div className="col-md-3">
                              <label htmlFor="zip" className="form-label">Zip</label>
                              <input type="text" className="form-control" id="zip" placeholder="Zip" required />
                          </div>
                      </div>
                      <hr className="my-4" />
                      <button className="w-100 btn btn-primary btn-lg" type="submit">Continue to
                          checkout
                      </button>
                  </form>
              </div>
          </div>
      </Wrapper>
  )
}

export default Home;