import { Col, Container, Row } from "react-bootstrap";
import NavbarMenu from "../navigation/NavbarMenu";
import Sidebar from "../navigation/Sidebar";
import CreateTask from "./CreateTask";
import { useState } from "react";

export default function Tasks() {
	const [showModal, setShowModal] = useState(false);

	const handleCloseModal = () => setShowModal(false);
	const handleOpenModal = () => setShowModal(true);

	return (
		<>
			<Sidebar />
			<NavbarMenu pageName={"Tasks"} />
			<Container className='tasks mt-5'>
				<Col>
					<CreateTask show={showModal} handleOpenModal={handleOpenModal} handleCloseModal={handleCloseModal} />
				</Col>
                <Row>
                    
                </Row>
			</Container>
		</>
	);
}
