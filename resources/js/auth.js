import { APIHelper } from './axios';

const recover = data => APIHelper.post('/user/recovery/password/id', data);

export {
  recover,
};
