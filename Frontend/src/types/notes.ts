export interface UserNotesData {
	id: number;
	name: string;
	tag: string;
	created_at: string;
	important: boolean;
	savedToHistory: boolean;
}

export interface UserNotesDataJoined {
	id: number;
	name: string;
	tag: string;
	created_at: string;
	dateSaved: string;
	important: boolean;
	savedToHistory: boolean;
}
