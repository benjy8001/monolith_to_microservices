import Wrapper from "../components/Wrapper";
import {useRouter} from "next/router";
import {useEffect, useState} from "react";
import axios from "axios";
import constants from "../constants";

declare var Stripe;

//@todo : fix : do not post if quantiy if empty for product
//@todo : feature : add total number of products

const Home = () => {
    const router = useRouter();
    const {code} = router.query;
    const [user, setUser] = useState(null);
    const [products, setProducts] = useState([]);
    const [quantities, setQuantities] = useState([]);
    const [firstName, setFirstName] = useState('');
    const [lastName, setLastName] = useState('');
    const [email, setEmail] = useState('');
    const [address, setAddress] = useState('');
    const [address2, setAddress2] = useState('');
    const [country, setCountry] = useState('');
    const [city, setCity] = useState('');
    const [zip, setZip] = useState('');

    useEffect(() => {
        if (undefined !== code) {
            (
                async () => {
                    const response = await axios.get(`${constants.BASE_URL}links/${code}`);
                    const data = response.data.data;

                    setUser(data.user);
                    setProducts(data.products);
                    setQuantities(data.products.map(p => {
                        return {
                            product_id: p.id,
                            quantity: 0
                        };
                    }));
                }
            )();
        }
    }, [code]);

    const change = (id: number, quantity: number) => {
        setQuantities(quantities.map(q => {
            if (q.product_id === id) {
                return {
                    product_id: id,
                    quantity: quantity
                };
            }

            return q;
        }));
    }

    const quantity = (id: number) => {
        const q = quantities.find(q => q.product_id === id);

        return q ? q.quantity : 0;
    }

    const total = () => {
        let t = 0;
        quantities.forEach(q => {
            const product = products.find(p => p.id === q.product_id);
            t += q.quantity * product.price;
        });

        return t;
    }

    const submit = async (e) => {
        e.preventDefault();
        console.log({
            first_name: firstName,
            last_name: lastName,
            email: email,
            address: address,
            address2: address2,
            country: country,
            city: city,
            zip: zip,
            code: code,
            items: quantities
        });
        const response = await axios.post(`${constants.BASE_URL}orders`, {
            first_name: firstName,
            last_name: lastName,
            email: email,
            address: address,
            address2: address2,
            country: country,
            city: city,
            zip: zip,
            code: code,
            items: quantities
        });
        const stripe = new Stripe(constants.STRIPE_KEY);
        stripe.redirectToCheckout({
            sessionId: response.data.id
        });
    }

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
                              <div key={p.id}>
                                  <li className="list-group-item d-flex justify-content-between lh-sm">
                                      <div>
                                          <h6 className="my-0">{p.title}</h6>
                                          <small className="text-muted">{p.description}</small>
                                      </div>
                                      <span className="text-muted">{p.price}€</span>
                                  </li>
                                  <li className="list-group-item d-flex justify-content-between lh-sm">
                                      <div>
                                          <h6 className="my-0">Quantity</h6>
                                      </div>
                                      <input type="number" min="0" className="text-muted form-control" style={{width: '65px'}}
                                             defaultValue={quantity(p.id)}
                                             onChange={e => change(p.id, parseInt(e.target.value))}/>
                                  </li>
                              </div>
                          );
                      })}
                      <li className="list-group-item d-flex justify-content-between">
                          <span>Total (EUR)</span>
                          <strong>{total()}€</strong>
                      </li>
                  </ul>
              </div>
              <div className="col-md-7 col-lg-8">
                  <h4 className="mb-3">Payment Info</h4>
                  <form className="needs-validation" onSubmit={submit}>
                      <div className="row g-3">
                          <div className="col-sm-6">
                              <label htmlFor="firstName" className="form-label">First name</label>
                              <input type="text" className="form-control" id="firstName" placeholder="First name"
                                     onChange={e => setFirstName(e.target.value)}
                                     required />
                          </div>

                          <div className="col-sm-6">
                              <label htmlFor="lastName" className="form-label">Last name</label>
                              <input type="text" className="form-control" id="lastName" placeholder="Last name"
                                     onChange={e => setLastName(e.target.value)}
                                     required />
                          </div>

                          <div className="col-12">
                              <label htmlFor="email" className="form-label">Email</label>
                              <input type="email" className="form-control" id="email" placeholder="you@example.com"
                                     onChange={e => setEmail(e.target.value)}
                                     required />
                          </div>

                          <div className="col-12">
                              <label htmlFor="address" className="form-label">Address</label>
                              <input type="text" className="form-control" id="address" placeholder="1234 Main St"
                                     onChange={e => setAddress(e.target.value)}
                                     required />
                          </div>

                          <div className="col-12">
                              <label htmlFor="address2" className="form-label">Address 2 <span
                                  className="text-muted">(Optional)</span></label>
                              <input type="text" className="form-control" id="address2"
                                     onChange={e => setAddress2(e.target.value)}
                                     placeholder="Apartment or suite" />
                          </div>

                          <div className="col-md-5">
                              <label htmlFor="country" className="form-label">Country</label>
                              <input type="text" className="form-control" id="country" placeholder="France"
                                     onChange={e => setCountry(e.target.value)} />
                          </div>

                          <div className="col-md-4">
                              <label htmlFor="city" className="form-label">City</label>
                              <input type="text" className="form-control" id="city" placeholder="City"
                                     onChange={e => setCity(e.target.value)} />
                          </div>

                          <div className="col-md-3">
                              <label htmlFor="zip" className="form-label">Zip</label>
                              <input type="text" className="form-control" id="zip" placeholder="Zip"
                                     onChange={e => setZip(e.target.value)}
                                     required />
                          </div>
                      </div>
                      <hr className="my-4" />
                      <button className="w-100 btn btn-primary btn-lg" type="submit">Checkout
                      </button>
                  </form>
              </div>
          </div>
      </Wrapper>
  )
}

export default Home;