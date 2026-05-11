import { Button } from "react-bootstrap";
import { IoIosStarOutline, IoIosStar } from "react-icons/io";

export default function SetNoteImportant({ noteId, importantNote, handleImportantNote }: { noteId: number; importantNote: boolean; handleImportantNote: (id: number, important: boolean) => void }) {
	return (
		<>
			<Button className='display-note__important-btn' onClick={() => handleImportantNote(noteId, importantNote)}>
				{importantNote ? <IoIosStar size={24} color='#caae11' /> : <IoIosStarOutline size={24} color='#b6b6b6' />}
			</Button>
		</>
	);
}
