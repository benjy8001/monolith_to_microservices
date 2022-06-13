import Wrapper from "../components/Wrapper";
import {useRouter} from "next/router";

const Home = () => {
    const router = useRouter();
    const {code} = router.query;

    return (
      <Wrapper>
          <div className="py-5 text-center">
                  <h2>Checkout form {code}</h2>
                  <p className="lead">Below is an example form built entirely with Bootstrap’s form controls. Each
                      required form group has a validation state that can be triggered by attempting to submit the
                      form without completing it.</p>
          </div>

          <div className="row g-5">
              <div className="col-md-5 col-lg-4 order-md-last">
                  <h4 className="d-flex justify-content-between align-items-center mb-3">
                      <span className="text-primary">Your cart</span>
                      <span className="badge bg-primary rounded-pill">3</span>
                  </h4>
                  <ul className="list-group mb-3">
                      <li className="list-group-item d-flex justify-content-between lh-sm">
                          <div>
                              <h6 className="my-0">Product name</h6>
                              <small className="text-muted">Brief description</small>
                          </div>
                          <span className="text-muted">$12</span>
                      </li>
                      <li className="list-group-item d-flex justify-content-between">
                          <span>Total (USD)</span>
                          <strong>$20</strong>
                      </li>
                  </ul>
              </div>
              <div className="col-md-7 col-lg-8">
                  <h4 className="mb-3">Billing address</h4>
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