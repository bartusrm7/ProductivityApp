import { useEffect } from "react";
import { Button, Col, Container, Form } from "react-bootstrap";
import { Link } from "react-router";

export default function SignIn() {
	useEffect(() => {
		document.title = "ProductivityApp - Sign In";
	});

	return (
		<div className='d-flex justify-content-center align-items-center min-vh-100'>
			<Container>
				<Col sm='10' md='8' lg='6' xl='5' xxl='4'>
					<div className='mb-4 text-center'>
						<h2>Sign In</h2>
						<p>To visit on dashboard please sign in.</p>
					</div>
					<Form>
						<Form.Group className='mb-3'>
							<Form.Floating>
								<Form.Control type='email' placeholder='Address email' />
								<Form.Label>Address email</Form.Label>
							</Form.Floating>
						</Form.Group>

						<Form.Group className='mb-3'>
							<Form.Floating>
								<Form.Control type='password' placeholder='Password' />
								<Form.Label>Password</Form.Label>
							</Form.Floating>
						</Form.Group>

						<Button className='w-100'>Login</Button>
					</Form>

					<div className='mt-4 d-flex justify-content-center'>
						<p className='me-1'>If you have not an account, please</p>
						<Link to='/sign-up'>sign up</Link>.
					</div>
				</Col>
			</Container>
		</div>
	);
}
