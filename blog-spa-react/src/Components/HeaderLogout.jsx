import { useContext, useState } from 'react'
import axios from 'axios'

// Context
import DispatchContext from '../DispatchContext'

const HeaderLogout = () => {
  const appDispatch = useContext(DispatchContext)
  const [state, setState] = useState({
    email: '',
    password: ''
  })

  const handleSubmit = e => {
    e.preventDefault()

    const user = {
      email: state.email,
      password: state.password
    }

    axios.post('user/login', user)
      .then(response => {
        appDispatch({ type: 'login', data: response.data.data })
        appDispatch({
          type: 'addToast',
          data: {
            title: 'User logged!',
            type: 'success',
            message: 'Login successfully'
          }
        })
      })
      .catch(e => {
        appDispatch({
          type: 'addToast',
          data: {
            title: 'Login Error!',
            type: 'danger',
            message: 'Incorrect email or password'
          }
        })
        console.error(e.message)
      })
  }

  return (
    <form onSubmit={handleSubmit} className="d-flex ms-lg-4">
      <input onChange={e => setState(prev => { return { ...prev, email: e.target.value } })} className="form-control me-2" type="email" placeholder="Email..." autoComplete="off"/>
      <input onChange={e => setState(prev => { return { ...prev, password: e.target.value } })} className="form-control me-2" type="password" placeholder="Password..." autoComplete="off"/>
      <button className="btn btn-outline-light d-flex align-items-center" type="submit">
        Login <i className="fa-solid fa-arrow-right-to-bracket ms-1"></i>
      </button>
    </form>
  )
}

export default HeaderLogout
