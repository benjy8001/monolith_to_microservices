import React from 'react';
import './App.css';
import {BrowserRouter, Route, Routes} from "react-router-dom";
import Login from "./public/Login";
import Main from './pages/Main';
import Register from "./public/Register";

function App() {
  return (
    <div className="App">
      <BrowserRouter>
          <Routes>
              <Route path='/' element={<Main />} />
              <Route path='/login' element={<Login />} />
              <Route path='/register' element={<Register />} />
          </Routes>
      </BrowserRouter>
    </div>
  );
}

export default App;
