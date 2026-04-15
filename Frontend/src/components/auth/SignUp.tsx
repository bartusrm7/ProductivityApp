import { useEffect } from "react";
import { Button, Col, Container, Form } from "react-bootstrap";
import { Link } from "react-router";

export default function SignUp() {
	useEffect(() => {
		document.title = "ProductivityApp - Sign Up";
	});

	return (
		<div className='d-flex justify-content-center align-items-center min-vh-100'>
			<Container>
				<Col sm='10' md='8' lg='6' xl='5' xxl='4'>
					<div className='mb-4 text-center'>
						<h2>Sign Up</h2>
						<p>Register new account to improve your life.</p>
					</div>
					<Form>
						<Form.Group className='mb-3'>
							<Form.Floating>
								<Form.Control type='text' placeholder='User name' />
								<Form.Label>User name</Form.Label>
							</Form.Floating>
						</Form.Group>

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

						<Button className='w-100'>Create account</Button>
					</Form>

					<div className='mt-4 d-flex justify-content-center'>
						<p className='me-1'>Already have an account?</p>
						<Link to='/sign-in'>Sign in</Link>.
					</div>
				</Col>
			</Container>
		</div>
	);
}
