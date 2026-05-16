import { MdDownloadDone } from "react-icons/md";

export default function SaveNoteToHistory({ noteId, saveToHistory, handleSaveToHistory }: { noteId: number; saveToHistory: boolean; handleSaveToHistory: (noteId: number, saveToHistory: boolean) => void }) {
	return (
		<>
			<button className='action-btn success-action-btn me-2' onClick={() => handleSaveToHistory(noteId, saveToHistory)}>
				<MdDownloadDone size={24} />
			</button>
		</>
	);
}
