import { useContext, useState } from 'react'
import { Link } from 'react-router-dom'

// Context
import StateContext from '../StateContext'

// Components
import HeaderLogin from './HeaderLogin'
import HeaderLogout from './HeaderLogout'

const Header = () => {
  const appState = useContext(StateContext)

  const [mobileNavActive, setMobileNavActive] = useState(false)

  const toggleNav = () => setMobileNavActive(prev => !prev)

  return (
    <header>
      <nav className="navbar navbar-dark navbar-expand-lg bg-primary">
        <div className="container-fluid">
          <Link to='/' className="navbar-brand" href="#">BLOG APP</Link>
          <button onClick={toggleNav} className="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
            <span className="navbar-toggler-icon"></span>
          </button>
          <div className={'collapse navbar-collapse ' + (mobileNavActive && 'show')} id="navbarNavAltMarkup">
            <div className="navbar-nav ms-auto">
              <Link className="nav-link active" aria-current="page" to="/"><i className="fa-solid fa-house"></i> Home</Link>
              <a className="nav-link" href="#"><i className="fa-solid fa-list"></i> Categories</a>

              {appState.isLoggedIn ? <HeaderLogin/> : <HeaderLogout/>}
            </div>
          </div>
        </div>
      </nav>
    </header>
  )
}

export default Header
