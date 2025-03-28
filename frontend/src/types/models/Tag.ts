
export enum TagColor {
  Red = 'red',
  Blue = 'blue',
  Green = 'green',
  Yellow = 'yellow',
  Purple = 'purple',
  Orange = 'orange',
  Black = 'black'
}

export interface Tag {
  id: number;
  name: string;
  color: TagColor;
}