import { Button, Modal, Form } from "react-bootstrap";
import type { UserNotesData } from "../../types/notes";
import { useState } from "react";
import { CiEdit } from "react-icons/ci";

export default function EditNote({ noteProp }: { noteProp: UserNotesData }) {
	const [noteData, setNoteData] = useState<UserNotesData>({ id: 0, name: "", tag: "", created_at: "", important: false });
	const [showModal, setShowModal] = useState<boolean>(false);
	const [errorsArray, setErrorsArray] = useState<string[]>([]);

	const handleOpenModal = () => {
		setShowModal(true);
		setNoteData(noteProp);
	};

	const handleCloseModal = () => {
		setShowModal(false);
	};

	async function handleEditNote(e: any) {
		e.preventDefault();
		try {
			const jwt = localStorage.getItem("jwt");
			const response = await fetch("http://productivityapp.local/edit-note", {
				method: "POST",
				headers: {
					"Content-Type": "application/json",
					Authorization: `Bearer ${jwt}`,
				},
				body: JSON.stringify(noteData),
			});
			const data = await response.json();
			if (data.errors) {
				setErrorsArray(data.errors);
			} else {
				setShowModal(false);
			}
		} catch (error) {
			setErrorsArray(["Server error. Try again."]);
		}
	}

	return (
		<>
			<button className='action-btn edit-action-btn me-2' onClick={handleOpenModal}>
				<CiEdit size={24} />
			</button>

			{showModal && (
				<Modal show={showModal} onHide={handleCloseModal}>
					<Modal.Header closeButton>
						<Modal.Title>Edit current Note</Modal.Title>
					</Modal.Header>
					<Modal.Body>
						<Form onSubmit={handleEditNote}>
							<Form.Group className='mb-3'>
								<Form.Floating>
									<Form.Control value={noteData.name} onChange={e => setNoteData(prevState => ({ ...prevState, name: e.target.value }))} type='text' placeholder='' />
									<Form.Label>Note name</Form.Label>
								</Form.Floating>
							</Form.Group>
							<Form.Group className='mb-3'>
								<Form.Floating>
									<Form.Control value={noteData.tag} onChange={e => setNoteData(prevState => ({ ...prevState, tag: e.target.value }))} type='text' placeholder='' />
									<Form.Label>Note tag</Form.Label>
								</Form.Floating>
							</Form.Group>

							{errorsArray.length > 0 && (
								<div>
									{errorsArray.map((error, index) => (
										<div key={index} className='alert alert-danger'>
											{error}
										</div>
									))}
								</div>
							)}

							<Button className='custom-btn w-100' type='submit'>
								Edit
							</Button>
						</Form>
					</Modal.Body>
				</Modal>
			)}
		</>
	);
}
