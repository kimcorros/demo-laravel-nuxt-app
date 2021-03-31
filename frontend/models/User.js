import { Model } from '@vuex-orm/core'
import Company from './Company'

export default class User extends Model {
  // This is the name used as module name of the Vuex Store.
  static entity = 'users'

  // List of all fields (schema) of the post model. `this.attr` is used
  // for the generic field type. The argument is the default value.
  static fields() {
    return {
      id: this.attr(null),
      company_id: this.attr(''),
      company: this.belongsTo(Company, 'company_id'),
      first_name: this.attr(''),
      last_name: this.attr(''),
      roles: this.attr(null),
      phone: this.attr(''),
      email: this.attr(''),
      created_at: this.attr(''),
      email_verified_at: this.attr(''),
      updated_at: this.attr(''),
    }
  }
}
