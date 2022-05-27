import React from 'react';
import './App.css';
import Dashboard from './secure/dashboard/Dashboard';
import Users from "./secure/users/Users";
import {BrowserRouter, Routes, Route} from 'react-router-dom';
import Login from "./public/Login";
import Register from "./public/Register";
import UserCreate from "./secure/users/UserCreate";
import UserEdit from "./secure/users/UserEdit";
import Roles from "./secure/roles/Roles";

function App() {
  return (
      <div className="App">
        <BrowserRouter>
            <Routes>
                <Route path='/' element={<Dashboard />} />
                <Route path='/login' element={<Login />} />
                <Route path='/register' element={<Register />} />
                <Route path='/users' element={<Users />} />
                <Route path='/users/create' element={<UserCreate />} />
                <Route path='/users/:id/edit' element={<UserEdit />} />
                <Route path='/roles' element={<Roles />} />
            </Routes>
        </BrowserRouter>
      </div>
  );
}

export default App;
