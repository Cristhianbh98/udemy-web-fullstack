import { useContext } from 'react'
import { Link } from 'react-router-dom'

// Context
import StateContext from '../StateContext'

// Components
import Page from './Page'

const Home = () => {
  const appState = useContext(StateContext)
  return (
    <Page title='Your Feed'>
      <div className="p-5 my-4 bg-light rounded-3">
        <div className="container-fluid py-5 text-center">
          <h1 className="display-5 fw-bold">Hello! {appState.user.name} {appState.user.surname}</h1>
          <p className="col-md-8 fs-4 mx-auto">It appears you dont have any post yet!</p>
          <Link to='/create-post' className="btn btn-primary btn-lg" type="button">Create One!</Link>
        </div>
      </div>
    </Page>
  )
}

export default Home
